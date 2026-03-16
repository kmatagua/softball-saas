import { computed, ref, watch } from 'vue';

export default {
    name: 'LineupPanel',
    props: {
        game: Object,
        lineups: Array,
        homeRoster: Array,
        awayRoster: Array
    },
    emits: ['update-reserve', 'substitute', 'save-lineups'],
    setup(props, { emit }) {
        const activeTab = ref('away'); 
        const showRosterModal = ref(false);
        const selectedPlayerForSub = ref(null);
        
        // Estado local para edición de lineup pre-juego
        const localHomeLineup = ref([]);
        const localAwayLineup = ref([]);
        const localHomeReserve = ref([]);
        const localAwayReserve = ref([]);

        // Sincronizar estado local cuando cambian los props (solo si no es live)
        watch(() => props.lineups, (newLineups) => {
            if (props.game.status !== 'live') {
                syncLocalLineups();
            }
        }, { immediate: true });

        function syncLocalLineups() {
            const hId = props.game.home_team.id;
            const aId = props.game.away_team.id;

            localHomeLineup.value = props.lineups
                .filter(l => l.team_id === hId && l.batting_order !== null)
                .sort((a, b) => a.batting_order - b.batting_order)
                .map(l => ({ ...l }));

            localAwayLineup.value = props.lineups
                .filter(l => l.team_id === aId && l.batting_order !== null)
                .sort((a, b) => a.batting_order - b.batting_order)
                .map(l => ({ ...l }));

            localHomeReserve.value = props.lineups
                .filter(l => l.team_id === hId && l.batting_order === null)
                .map(l => ({ ...l }));

            localAwayReserve.value = props.lineups
                .filter(l => l.team_id === aId && l.batting_order === null)
                .map(l => ({ ...l }));
        }

        const currentLineup = computed(() => {
            if (props.game.status === 'live') {
                const teamId = activeTab.value === 'home' ? props.game.home_team.id : props.game.away_team.id;
                return props.lineups
                    .filter(l => l.team_id === teamId && l.batting_order !== null && l.is_active)
                    .sort((a, b) => a.batting_order - b.batting_order);
            }
            return activeTab.value === 'home' ? localHomeLineup.value : localAwayLineup.value;
        });

        const currentReserve = computed(() => {
            if (props.game.status === 'live') {
                const teamId = activeTab.value === 'home' ? props.game.home_team.id : props.game.away_team.id;
                return props.lineups
                    .filter(l => l.team_id === teamId && l.batting_order === null && l.is_active);
            }
            return activeTab.value === 'home' ? localHomeReserve.value : localAwayReserve.value;
        });

        const availableRoster = computed(() => {
            const teamId = activeTab.value === 'home' ? props.game.home_team.id : props.game.away_team.id;
            const teamRoster = activeTab.value === 'home' ? props.homeRoster : props.awayRoster;
            
            let assignedIds = [];
            if (props.game.status === 'live') {
                assignedIds = props.lineups.filter(l => l.team_id === teamId).map(l => l.player_id);
            } else {
                const lineup = activeTab.value === 'home' ? localHomeLineup.value : localAwayLineup.value;
                const reserve = activeTab.value === 'home' ? localHomeReserve.value : localAwayReserve.value;
                assignedIds = [...lineup, ...reserve].map(l => l.player_id);
            }
            
            return teamRoster.filter(p => !assignedIds.includes(p.id));
        });

        const isReserveLocked = computed(() => {
            return props.game.status === 'live' && props.game.current_inning > 3;
        });

        // Metodos Pre-Juego
        const addToLineupPre = (player) => {
            const list = activeTab.value === 'home' ? localHomeLineup : localAwayLineup;
            list.value.push({
                player_id: player.id,
                player_name: player.first_name + ' ' + player.last_name,
                batting_order: list.value.length + 1,
                field_position: 'LF', // Default
                is_active: true
            });
        };

        const addToReservePre = (player) => {
            const list = activeTab.value === 'home' ? localHomeReserve : localAwayReserve;
            list.value.push({
                player_id: player.id,
                player_name: player.first_name + ' ' + player.last_name,
                batting_order: null,
                field_position: 'BN',
                is_active: true
            });
        };

        const removeFromListPre = (player, listType) => {
            const list = activeTab.value === 'home' 
                ? (listType === 'lineup' ? localHomeLineup : localHomeReserve)
                : (listType === 'lineup' ? localAwayLineup : localAwayReserve);
            
            list.value = list.value.filter(p => p.player_id !== player.player_id);
            
            // Reordenar si era lineup
            if (listType === 'lineup') {
                list.value.forEach((p, idx) => p.batting_order = idx + 1);
            }
        };

        const saveAllLineups = () => {
            emit('save-lineups', {
                home_lineup: localHomeLineup.value,
                away_lineup: localAwayLineup.value,
                home_reserve: localHomeReserve.value,
                away_reserve: localAwayReserve.value
            });
        };

        // Metodos In-Game
        const addToReserve = (player) => {
            if (props.game.status !== 'live') {
                addToReservePre(player);
                return;
            }
            const teamId = activeTab.value === 'home' ? props.game.home_team.id : props.game.away_team.id;
            emit('update-reserve', teamId, player.id, 'add');
        };

        const startSubstitution = (outgoingPlayer) => {
            selectedPlayerForSub.value = outgoingPlayer;
            showRosterModal.value = true;
        };

        const confirmSubstitution = (incomingPlayer) => {
            const teamId = activeTab.value === 'home' ? props.game.home_team.id : props.game.away_team.id;
            const position = selectedPlayerForSub.value.field_position;
            emit('substitute', teamId, selectedPlayerForSub.value.player_id, incomingPlayer.player_id, position);
            showRosterModal.value = false;
            selectedPlayerForSub.value = null;
        };

        return {
            activeTab, showRosterModal, selectedPlayerForSub,
            currentLineup, currentReserve, availableRoster, isReserveLocked,
            addToLineupPre, addToReservePre, removeFromListPre, saveAllLineups,
            addToReserve, startSubstitution, confirmSubstitution
        };
    },
    template: `
        <div class="bg-dark-card border border-dark-border rounded-xl overflow-hidden shadow-xl">
            <!-- Tabs -->
            <div class="flex border-b border-dark-border">
                <button @click="activeTab = 'away'" 
                    class="flex-1 py-3 text-xs font-bold uppercase tracking-wider transition-all"
                    :class="activeTab === 'away' ? 'bg-amber-500/20 text-amber-400 border-b-2 border-amber-500' : 'text-slate-500 hover:bg-white/5'">
                    {{ game.away_team.name }}
                </button>
                <button @click="activeTab = 'home'" 
                    class="flex-1 py-3 text-xs font-bold uppercase tracking-wider transition-all"
                    :class="activeTab === 'home' ? 'bg-brand-500/20 text-brand-400 border-b-2 border-brand-500' : 'text-slate-500 hover:bg-white/5'">
                    {{ game.home_team.name }}
                </button>
            </div>

            <div class="p-4">
                <!-- Lineup Section -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-[10px] font-black uppercase text-slate-500 tracking-widest">Lineup</h4>
                        <button v-if="game.status === 'scheduled'" @click="saveAllLineups" class="text-[10px] bg-emerald-500/20 text-emerald-400 px-3 py-1 rounded-lg border border-emerald-500/30 hover:bg-emerald-500/30 transition-all font-bold">
                            Guardar Lineups
                        </button>
                    </div>
                    
                    <div class="space-y-1 max-h-64 overflow-y-auto pr-1">
                        <div v-for="l in currentLineup" :key="l.player_id" class="flex items-center gap-3 p-2 rounded-lg bg-white/5 border border-white/5 group">
                            <span class="w-4 text-center font-mono text-[10px] text-slate-500">{{ l.batting_order }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-white truncate">{{ l.player_name }}</p>
                                <select v-if="game.status === 'scheduled'" v-model="l.field_position" class="bg-transparent text-[10px] text-brand-400 uppercase font-mono border-none p-0 focus:ring-0">
                                    <option value="P">P</option><option value="C">C</option><option value="1B">1B</option>
                                    <option value="2B">2B</option><option value="3B">3B</option><option value="SS">SS</option>
                                    <option value="LF">LF</option><option value="CF">CF</option><option value="RF">RF</option>
                                    <option value="DH">DH</option>
                                </select>
                                <p v-else class="text-[10px] text-slate-500 uppercase font-mono">{{ l.field_position }}</p>
                            </div>
                            
                            <div class="flex gap-1">
                                <button v-if="game.status === 'live'" @click="startSubstitution(l)" class="p-1.5 rounded bg-amber-500/20 text-amber-400 hover:bg-amber-500/30 transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                </button>
                                <button v-if="game.status === 'scheduled'" @click="removeFromListPre(l, 'lineup')" class="p-1 text-slate-600 hover:text-red-400">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                        <div v-if="currentLineup.length === 0" class="text-center py-4 text-[10px] text-slate-600 italic bg-white/5 rounded-lg border border-dashed border-white/5">
                            Vacío. Añade jugadores del roster.
                        </div>
                    </div>
                </div>

                <!-- Reserve Section -->
                <div>
                    <div class="flex items-center justify-between mb-3 border-t border-dark-border pt-4">
                        <h4 class="text-[10px] font-black uppercase text-slate-500 tracking-widest">Reserva / Banca</h4>
                        
                        <div v-if="!isReserveLocked" class="relative group">
                            <button class="text-[10px] bg-brand-500/10 text-brand-400 px-2 py-0.5 rounded-full border border-brand-500/20 hover:bg-brand-500/20 transition-all flex items-center gap-1">
                                <span>+ Añadir Roster</span>
                            </button>
                            <div class="absolute right-0 bottom-full mb-2 w-48 bg-slate-900 border border-dark-border rounded-xl shadow-2xl py-2 z-50 hidden group-hover:block max-h-48 overflow-y-auto">
                                <div v-for="p in availableRoster" :key="p.id" class="flex items-center justify-between px-3 py-1.5 hover:bg-white/5 group/p">
                                    <span class="text-[10px] text-slate-300">{{ p.first_name }} {{ p.last_name }}</span>
                                    <div class="flex gap-1 opacity-100 sm:opacity-0 group-hover/p:opacity-100 transition-opacity">
                                        <button @click="addToLineupPre(p)" v-if="game.status === 'scheduled'" class="text-[9px] bg-emerald-500/20 text-emerald-400 px-1.5 rounded border border-emerald-500/30">LINEUP</button>
                                        <button @click="addToReserve(p)" class="text-[9px] bg-amber-500/20 text-amber-400 px-1.5 rounded border border-amber-500/30">RES</button>
                                    </div>
                                </div>
                                <div v-if="availableRoster.length === 0" class="px-4 py-2 text-[10px] text-slate-600 italic">No hay más jugadores</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-1 max-h-40 overflow-y-auto pr-1">
                        <div v-for="r in currentReserve" :key="r.player_id" class="flex items-center justify-between p-2 rounded-lg bg-slate-800/40 border border-white/5">
                            <span class="text-[11px] font-medium text-slate-400 truncate">{{ r.player_name }}</span>
                            <button v-if="!isReserveLocked" @click="game.status === 'live' ? emit('update-reserve', r.team_id, r.player_id, 'remove') : removeFromListPre(r, 'reserve')" class="text-slate-600 hover:text-red-400 p-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Sustitucion (igual que antes) -->
            <div v-if="showRosterModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
                <div class="bg-dark-card border border-dark-border w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                    <div class="p-6 border-b border-dark-border flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-white">Sustitución</h3>
                            <p class="text-xs text-slate-500">Saliendo: <span class="text-amber-400">{{ selectedPlayerForSub?.player_name }}</span></p>
                        </div>
                        <button @click="showRosterModal = false" class="text-slate-400 hover:text-white"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <div class="max-h-64 overflow-y-auto p-4 space-y-2">
                        <button v-for="r in currentReserve" :key="r.id" @click="confirmSubstitution(r)"
                            class="w-full flex items-center justify-between p-3 rounded-xl bg-white/5 hover:bg-brand-500/20 hover:text-brand-400 border border-transparent hover:border-brand-500/30 transition-all group">
                            <span class="text-sm font-bold">{{ r.player_name }}</span>
                            <svg class="w-4 h-4 opacity-0 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `
};
