import { ref, computed, onMounted } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';
import ScoreboardHeader from './ScoreboardHeader.js';
import BaseballDiamond from './BaseballDiamond.js';
import ActionPanel from './ActionPanel.js';

export default {
    name: 'ScorekeeperApp',
    components: {
        ScoreboardHeader,
        BaseballDiamond,
        ActionPanel
    },
    props: {
        endpoints: Object
    },
    setup(props) {
        const loading = ref(true);
        const error = ref(null);
        const game = ref(null);
        const events = ref([]);

        const fetchGameDetails = async () => {
            try {
                loading.value = true;
                const response = await axios.get(props.endpoints.fetchGameDetailsUrl);
                game.value = response.data.game;
                events.value = response.data.events || [];
            } catch (e) {
                console.error('Error fetching game details:', e);
                error.value = 'Failed to load game details. Please try again later.';
            } finally {
                loading.value = false;
            }
        };

        const handleRecordEvent = async (eventType) => {
            try {
                const response = await axios.post(props.endpoints.postEventUrl, {
                    game_id: props.endpoints.gameId,
                    event_type: eventType,
                    // TODO: Pasar pitcher y batter actual cuando exista en el estado
                    pitcher_id: null,
                    batter_id: null
                });

                // Agregamos el evento devuelto al inicio de la lista
                events.value.unshift(response.data.event);

                // Actualizamos marcadores si el API también los devuelve o volvemos a obtener
                if (response.data.event) {
                    game.value.home_score = response.data.event.home_score;
                    game.value.away_score = response.data.event.away_score;
                }

                // Opción segura: recargar todo si es muy complejo
                // await fetchGameDetails();
            } catch (e) {
                console.error('Error recording event:', e);
                alert('No se pudo procesar la jugada. ' + (e.response?.data?.message || ''));
            }
        };

        onMounted(() => {
            fetchGameDetails();
        });

        return {
            loading,
            error,
            game,
            events,
            handleRecordEvent
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
            
            <div v-else-if="error" class="bg-red-500/10 border border-red-500/20 rounded-xl p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <h3 class="text-lg font-medium text-red-400">{{ error }}</h3>
            </div>

            <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Parte izquierda: Marcador, Diamante y Play-By-Play -->
                <div class="lg:col-span-8 flex flex-col gap-6">
                    <ScoreboardHeader :game="game" :gameState="game.status === 'live' ? (events[0] || { inning_half: 'top', current_inning: 1, balls: 0, strikes: 0, outs: 0 }) : { inning_half: 'top', current_inning: 1, balls: 0, strikes: 0, outs: 0 }" />
                    
                    <div class="bg-dark-card rounded-xl border border-dark-border p-6 shadow-sm">
                        <BaseballDiamond :gameState="game.status === 'live' ? (events[0] || { bases: {} }) : { bases: {} }" />
                    </div>
                </div>

                <!-- Parte derecha: Panel de Acciones -->
                <div class="lg:col-span-4 flex flex-col gap-6">
                    <div class="bg-dark-card rounded-xl border border-dark-border p-6 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4">
                            <span class="flex h-3 w-3 relative">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </span>
                        </div>
                        <h2 class="text-xl font-bold mb-4">Panel de Anotador</h2>
                        
                        <ActionPanel @record-event="handleRecordEvent" />
                    </div>
                </div>
            </div>
        </div>
    `
};
