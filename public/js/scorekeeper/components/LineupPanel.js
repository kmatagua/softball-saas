import { computed } from 'vue';

export default {
    name: 'LineupPanel',
    props: {
        game: {
            type: Object,
            required: true
        },
        lineups: {
            type: Array,
            required: true
        }
    },
    setup(props) {
        const homeLineup = computed(() => {
            return props.lineups
                .filter(l => l.team_id === props.game.home_team.id)
                .sort((a, b) => a.batting_order - b.batting_order);
        });

        const awayLineup = computed(() => {
            return props.lineups
                .filter(l => l.team_id === props.game.away_team.id)
                .sort((a, b) => a.batting_order - b.batting_order);
        });

        return { homeLineup, awayLineup };
    },
    template: `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <!-- Away Lineup -->
            <div class="bg-dark-card border border-dark-border rounded-xl overflow-hidden">
                <div class="bg-amber-500/10 px-4 py-2 border-b border-dark-border flex items-center justify-between">
                    <h3 class="text-sm font-bold text-amber-400 uppercase tracking-tight">{{ game.away_team.name }} - Lineup</h3>
                    <span class="text-[10px] text-amber-500/50 font-mono">VISITANTE</span>
                </div>
                <div class="p-2">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="text-slate-500 border-b border-dark-border/50">
                                <th class="py-2 px-2 w-8">#</th>
                                <th class="py-2 px-2">Jugador</th>
                                <th class="py-2 px-2 text-right">Pos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="player in awayLineup" :key="player.id" class="border-b border-dark-border/30 last:border-0 hover:bg-white/5 transition-colors">
                                <td class="py-2 px-2 font-mono text-slate-400">{{ player.batting_order }}</td>
                                <td class="py-2 px-2 text-white font-medium">{{ player.player_name }}</td>
                                <td class="py-2 px-2 text-right text-slate-400 font-mono">{{ player.field_position }}</td>
                            </tr>
                            <tr v-if="awayLineup.length === 0">
                                <td colspan="3" class="py-4 text-center text-slate-500 italic">No hay lineup registrado</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Home Lineup -->
            <div class="bg-dark-card border border-dark-border rounded-xl overflow-hidden">
                <div class="bg-brand-500/10 px-4 py-2 border-b border-dark-border flex items-center justify-between">
                    <h3 class="text-sm font-bold text-brand-400 uppercase tracking-tight">{{ game.home_team.name }} - Lineup</h3>
                    <span class="text-[10px] text-brand-500/50 font-mono">HOMECLUB</span>
                </div>
                <div class="p-2">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="text-slate-500 border-b border-dark-border/50">
                                <th class="py-2 px-2 w-8">#</th>
                                <th class="py-2 px-2">Jugador</th>
                                <th class="py-2 px-2 text-right">Pos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="player in homeLineup" :key="player.id" class="border-b border-dark-border/30 last:border-0 hover:bg-white/5 transition-colors">
                                <td class="py-2 px-2 font-mono text-slate-400">{{ player.batting_order }}</td>
                                <td class="py-2 px-2 text-white font-medium">{{ player.player_name }}</td>
                                <td class="py-2 px-2 text-right text-slate-400 font-mono">{{ player.field_position }}</td>
                            </tr>
                            <tr v-if="homeLineup.length === 0">
                                <td colspan="3" class="py-4 text-center text-slate-500 italic">No hay lineup registrado</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `
};
