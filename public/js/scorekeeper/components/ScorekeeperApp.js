import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import ScoreboardHeader from './ScoreboardHeader.js';
import BaseballDiamond from './BaseballDiamond.js';
import ActionPanel from './ActionPanel.js';
import PlayByPlayList from './PlayByPlayList.js';
import LineupPanel from './LineupPanel.js';

axios.defaults.withCredentials = true;

export default {
    name: 'ScorekeeperApp',
    components: {
        ScoreboardHeader,
        BaseballDiamond,
        ActionPanel,
        PlayByPlayList,
        LineupPanel
    },
    props: {
        endpoints: Object
    },
    setup(props) {
        const game = ref(null);
        const events = ref([]);
        const lineups = ref([]);
        const balls = ref(0);
        const strikes = ref(0);
        const currentPitcherId = ref(null);
        const loading = ref(true);
        const error = ref(null);
        const homeRoster = ref([]);
        const awayRoster = ref([]);

        const fetchGameDetails = async () => {
            try {
                loading.value = true;
                const response = await axios.get(props.endpoints.fetchGameDetailsUrl);
                game.value = response.data.game;
                events.value = response.data.events;
                lineups.value = response.data.lineups || [];
                
                if (game.value && homeRoster.value.length === 0) {
                    fetchRosters();
                }
            } catch (err) {
                console.error('Error fetching game details:', err);
                error.value = 'Failed to load game details. Please try again later.';
            } finally {
                loading.value = false;
            }
        };

        const fetchRosters = async () => {
            try {
                const [homeRes, awayRes] = await Promise.all([
                    axios.get(`/api/teams/${game.value.home_team.id}/players`),
                    axios.get(`/api/teams/${game.value.away_team.id}/players`)
                ]);
                homeRoster.value = homeRes.data;
                awayRoster.value = awayRes.data;
            } catch (err) {
                console.error('Error fetching rosters:', err);
            }
        };

        const handleRecordEvent = async (eventType) => {
            try {
                const currentTeamId = game.value.half_inning === 'top' ? game.value.away_team.id : game.value.home_team.id;
                let currentPlayerId = game.value.half_inning === 'top' ? game.value.current_batter_away : game.value.current_batter_home;

                if (!currentPlayerId) {
                    const teamLineup = lineups.value.filter(l => l.team_id === currentTeamId && l.batting_order !== null);
                    if (teamLineup.length > 0) {
                        currentPlayerId = teamLineup[0].player_id;
                    }
                }

                if (!currentPlayerId) {
                    alert('No hay jugadores asignados al lineup de este equipo.');
                    return;
                }

                const opposingTeamId = game.value.half_inning === 'top' ? game.value.home_team.id : game.value.away_team.id;
                let pitcherId = currentPitcherId.value;
                if (!pitcherId) {
                    const pitcherLineup = lineups.value.find(l => l.team_id === opposingTeamId && l.field_position === 'P');
                    if (pitcherLineup) {
                        pitcherId = pitcherLineup.player_id;
                    }
                }

                const response = await axios.post(props.endpoints.postEventUrl, {
                    game_id: props.endpoints.gameId,
                    team_id: currentTeamId,
                    player_id: currentPlayerId,
                    event_type: eventType,
                    pitcher_id: pitcherId
                });

                events.value.unshift(response.data.event);

                if (response.data.score) {
                    game.value.home_score = response.data.score.home;
                    game.value.away_score = response.data.score.away;
                    game.value.outs = response.data.outs;
                    game.value.current_inning = response.data.inning;
                    game.value.half_inning = response.data.half;
                    game.value.bases = response.data.bases;

                    const resetTypes = ['single', 'double', 'triple', 'homerun', 'out', 'strikeout', 'walk', 'hbp', 'double_play'];
                    if (resetTypes.includes(eventType)) {
                        balls.value = 0;
                        strikes.value = 0;
                    } else if (eventType === 'strike') {
                        strikes.value++;
                    } else if (eventType === 'ball') {
                        balls.value++;
                    }
                }
            } catch (e) {
                console.error('Error recording event:', e);
                if (e.response?.status !== 201) {
                    alert('No se pudo procesar la jugada. ' + (e.response?.data?.message || ''));
                }
            }
        };

        const deleteEvent = async (eventId) => {
            try {
                loading.value = true;
                await axios.delete(`/api/game-events/${eventId}`);
                await fetchGameDetails();
            } catch (e) {
                console.error('Error deleting event:', e);
                alert('No se pudo eliminar la jugada. ' + (e.response?.data?.message || ''));
                loading.value = false;
            }
        };

        const handleUpdateReserve = async (teamId, playerId, action) => {
            try {
                await axios.post('/api/game-lineups/update-reserve', {
                    game_id: game.value.id,
                    team_id: teamId,
                    player_id: playerId,
                    action: action
                });
                await fetchGameDetails();
            } catch (e) {
                alert(e.response?.data?.message || 'Error al actualizar reserva');
            }
        };

        const handleSubstitute = async (teamId, outgoingId, incomingId, position) => {
            try {
                await axios.post('/api/game-lineups/substitute', {
                    game_id: game.value.id,
                    team_id: teamId,
                    outgoing_player_id: outgoingId,
                    incoming_player_id: incomingId,
                    field_position: position
                });
                await fetchGameDetails();
            } catch (e) {
                alert(e.response?.data?.message || 'Error en la sustitución');
            }
        };

        const handleSaveLineups = async (lineupData) => {
            try {
                loading.value = true;
                await axios.post('/api/save-lineups', {
                    game_id: game.value.id,
                    ...lineupData
                });
                await fetchGameDetails();
                alert('Lineups guardados correctamente.');
            } catch (e) {
                alert(e.response?.data?.message || 'Error al guardar lineups');
            } finally {
                loading.value = false;
            }
        };

        const handleStartGame = async () => {
            try {
                loading.value = true;
                await axios.post(`/api/games/${game.value.id}/start`);
                await fetchGameDetails();
            } catch (e) {
                alert(e.response?.data?.message || 'No se pudo iniciar el juego.');
            } finally {
                loading.value = false;
            }
        };

        onMounted(() => {
            fetchGameDetails();
        });

        return {
            loading, error, game, events, lineups, balls, strikes,
            handleRecordEvent, deleteEvent, handleUpdateReserve, handleSubstitute,
            handleSaveLineups, handleStartGame, homeRoster, awayRoster
        };
    },
    template: `
        <div class="scorekeeper-app text-white">
            <div v-if="loading" class="flex justify-center items-center py-20">
                <svg class="animate-spin h-10 w-10 text-brand-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="ml-3 text-lg text-slate-400">Cargando datos del partido...</span>
            </div>
            
            <div v-if="error" class="bg-red-500/10 border border-red-500/20 rounded-xl p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <h3 class="text-lg font-medium text-red-400">{{ error }}</h3>
            </div>

            <div v-if="!loading && !error && game" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-8 flex flex-col gap-6">
                    <ScoreboardHeader :game="game" :gameState="{ inning_half: game.half_inning, current_inning: game.current_inning, outs: game.outs, balls, strikes }" />
                    <div class="bg-dark-card rounded-xl border border-dark-border p-6 shadow-sm">
                        <BaseballDiamond :gameState="{ bases: game.bases }" />
                    </div>
                </div>

                <div class="lg:col-span-4 flex flex-col gap-6">
                    <div class="bg-dark-card rounded-xl border border-dark-border p-6 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4">
                            <span class="flex h-3 w-3 relative">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </span>
                        </div>
                        <h2 class="text-xl font-bold mb-4">Panel de Anotador</h2>
                        <ActionPanel :gameStatus="game.status" @record-event="handleRecordEvent" @start-game="handleStartGame" />
                    </div>
                    <PlayByPlayList :events="events" @delete-event="deleteEvent" />
                    <LineupPanel 
                        :game="game" :lineups="lineups"
                        :homeRoster="homeRoster" :awayRoster="awayRoster"
                        @update-reserve="handleUpdateReserve"
                        @substitute="handleSubstitute"
                        @save-lineups="handleSaveLineups"
                    />
                </div>
            </div>
        </div>
    `
};
