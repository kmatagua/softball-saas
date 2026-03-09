export default {
    name: 'PlayByPlayList',
    props: {
        events: {
            type: Array,
            required: true
        }
    },
    emits: ['delete-event', 'edit-event'],
    setup(props, { emit }) {
        const typeLabels = {
            'strike': 'Strike',
            'ball': 'Bola',
            'foul': 'Foul',
            'single': 'Sencillo (1B)',
            'double': 'Doble (2B)',
            'triple': 'Triple (3B)',
            'homerun': 'Home Run',
            'walk': 'Base por Bolas',
            'hbp': 'Golpeado (HBP)',
            'out': 'Out en jugada',
            'strikeout': 'Ponche (K)',
            'double_play': 'Doble Play',
            'run_scored': 'Carrera Anotada'
        };

        const getEventLabel = (type) => typeLabels[type] || type;

        const handleDelete = (id) => {
            if (confirm('¿Estás seguro de eliminar esta jugada? Esto recalculará todo el partido desde este punto en adelante.')) {
                emit('delete-event', id);
            }
        };

        return { getEventLabel, handleDelete };
    },
    template: `
        <div class="mt-8 border-t border-dark-border pt-6">
            <h3 class="text-sm font-bold text-slate-300 uppercase tracking-widest mb-4 flex items-center gap-2">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Historial de Jugadas (Play-by-Play)
            </h3>
            
            <div v-if="events.length === 0" class="text-center py-6 text-slate-500 text-sm">
                No hay jugadas registradas aún.
            </div>

            <div v-else class="space-y-3 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                <!-- Filtramos run_scored porque son generados automaticamente y editar el hit base debería eliminarlos -->
                <div v-for="event in events.filter(e => e.event_type !== 'run_scored')" :key="event.id" 
                     class="bg-dark-bg border border-dark-border rounded-lg p-3 flex items-center justify-between hover:border-brand-500/50 transition-colors">
                    
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-400">
                            INN {{ event.inning }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">{{ getEventLabel(event.event_type) }}</p>
                            <p class="text-xs text-slate-400" v-if="event.player">Bat: {{ event.player.first_name }} {{ event.player.last_name }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button @click="handleDelete(event.id)" class="p-1.5 text-slate-500 hover:text-red-400 hover:bg-red-400/10 rounded transition-colors" title="Eliminar y Recalcular">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    `
};
