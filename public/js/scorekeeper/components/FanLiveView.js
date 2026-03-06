import { ref, onMounted, onUnmounted } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';
import ScoreboardHeader from './ScoreboardHeader.js';
import BaseballDiamond from './BaseballDiamond.js';

export default {
    name: 'FanLiveView',
    components: {
        ScoreboardHeader,
        BaseballDiamond
    },
    props: {
        endpoints: Object
    },
    setup(props) {
        const loading = ref(true);
        const error = ref(null);
        const game = ref(null);
        const events = ref([]);
        let pollInterval = null;

        const fetchGameDetails = async () => {
            try {
                // Background update sin mostrar spinner de carga completo asiduamente
                const response = await axios.get(props.endpoints.fetchGameDetailsUrl);
                game.value = response.data.game;
                events.value = response.data.events || [];
                error.value = null;
            } catch (e) {
                console.error('Error fetching live game:', e);
                error.value = 'Desconectado de la transmisión. Intentando reconectar...';
            } finally {
                loading.value = false;
            }
        };

        onMounted(() => {
            fetchGameDetails();
            // Polling cada 5 segundos
            pollInterval = setInterval(fetchGameDetails, 5000);
        });

        onUnmounted(() => {
            if (pollInterval) clearInterval(pollInterval);
        });

        return {
            loading,
            error,
            game,
            events
        };
    },
    template: `
        <div class="text-white">
            <div v-if="loading" class="flex justify-center items-center py-20">
                <svg class="animate-spin h-10 w-10 text-brand-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            
            <div v-else-if="error && !game" class="bg-red-500/10 border border-red-500/20 rounded-xl p-6 text-center">
                <h3 class="text-lg font-medium text-red-400">{{ error }}</h3>
            </div>

            <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <div v-if="error" class="lg:col-span-12 bg-amber-500/10 border border-amber-500/20 rounded border px-4 py-2 text-amber-400 text-sm flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    {{ error }}
                </div>

                <!-- Scoreboard y Diamante -->
                <div class="lg:col-span-8 flex flex-col gap-6">
                    <ScoreboardHeader :game="game" :gameState="game.status === 'live' ? { inning_half: game.half_inning, current_inning: game.current_inning, balls: game.balls, strikes: game.strikes, outs: game.outs } : { inning_half: 'top', current_inning: 1, balls: 0, strikes: 0, outs: 0 }" />
                    
                    <div class="bg-dark-card rounded-xl border border-dark-border p-6 shadow-sm">
                        <BaseballDiamond :gameState="game.status === 'live' ? { bases: game.bases } : { bases: {} }" />
                    </div>
                </div>

                <!-- Play by Play (Solo Lectura) -->
                <div class="lg:col-span-4 flex flex-col gap-6">
                    <div class="bg-dark-card rounded-xl border border-dark-border p-6 shadow-sm h-full flex flex-col">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Jugada a Jugada
                            </h2>
                            <span v-if="game.status === 'live'" class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium bg-red-500/10 text-red-500 ring-1 ring-inset ring-red-500/20">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                LIVE
                            </span>
                            <span v-else class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium bg-slate-500/10 text-slate-400 ring-1 ring-inset ring-slate-500/20">
                                FINALIZADO
                            </span>
                        </div>
                        
                        <div class="flex-1 overflow-y-auto pr-2 space-y-4 max-h-[500px]">
                            <div v-if="events.length === 0" class="text-center py-8 text-slate-500 text-sm">
                                Aún no hay eventos registrados en este partido.
                            </div>
                            
                            <div v-for="event in events" :key="event.id" class="relative pl-4 pb-4 border-l border-dark-border last:border-0 last:pb-0">
                                <div class="absolute -left-1.5 top-1.5 h-3 w-3 rounded-full border-2 border-dark-card"
                                     :class="event.runs > 0 ? 'bg-brand-500' : 'bg-slate-600'"></div>
                                <div class="text-xs text-brand-400 font-bold mb-1">Inning {{ event.inning }} <span class="text-slate-500 mx-1">•</span> {{ event.team_name }}</div>
                                <p class="text-sm text-slate-300">
                                    <span class="font-semibold text-white capitalize">{{ event.event_type.replace('_', ' ') }}</span>
                                    <span v-if="event.runs > 0" class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-brand-500/20 text-brand-400">
                                        +{{ event.runs }} Carrera(s)
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `
};
