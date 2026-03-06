import { ref } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';

export default {
    name: 'ScoreboardHeader',
    props: {
        game: {
            type: Object,
            required: true
        },
        gameState: {
            type: Object,
            required: true
        }
    },
    template: `
        <div class="bg-dark-bg/80 border border-dark-border rounded-xl p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <!-- VisiTante -->
                <div class="text-center w-1/3">
                    <p class="text-xl font-bold text-amber-400">{{ game.away_team.name }}</p>
                    <p class="text-5xl font-black mt-2">{{ game.away_score || 0 }}</p>
                </div>
                
                <!-- Info Central -->
                <div class="text-center w-1/3 border-l border-r border-dark-border px-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded bg-slate-800 border border-slate-700 text-sm mb-3">
                        <span class="font-bold text-brand-400">{{ gameState.inning_half === 'top' ? '▲' : '▼' }}</span>
                        <span class="font-bold text-white">Inning {{ gameState.current_inning }}</span>
                    </div>
                </div>
                
                <!-- HomeClub -->
                <div class="text-center w-1/3">
                    <p class="text-xl font-bold text-brand-400">{{ game.home_team.name }}</p>
                    <p class="text-5xl font-black mt-2">{{ game.home_score || 0 }}</p>
                </div>
            </div>
            
            <!-- Bolas, Strikes, Outs -->
            <div class="grid grid-cols-3 gap-4 border-t border-dark-border pt-4">
                <div class="text-center">
                    <p class="text-sm text-slate-400 uppercase tracking-widest mb-2">Bolas</p>
                    <div class="flex justify-center gap-2">
                        <span v-for="n in 4" :key="'b'+n" class="h-4 w-4 rounded-full border border-emerald-500" :class="n <= gameState.balls ? 'bg-emerald-500' : 'bg-transparent'"></span>
                    </div>
                </div>
                <div class="text-center">
                    <p class="text-sm text-slate-400 uppercase tracking-widest mb-2">Strikes</p>
                    <div class="flex justify-center gap-2">
                        <span v-for="n in 3" :key="'s'+n" class="h-4 w-4 rounded-full border border-red-500" :class="n <= gameState.strikes ? 'bg-red-500' : 'bg-transparent'"></span>
                    </div>
                </div>
                <div class="text-center">
                    <p class="text-sm text-slate-400 uppercase tracking-widest mb-2">Outs</p>
                    <div class="flex justify-center gap-2">
                        <span v-for="n in 3" :key="'o'+n" class="h-4 w-4 rounded-full border border-amber-500" :class="n <= gameState.outs ? 'bg-amber-500' : 'bg-transparent'"></span>
                    </div>
                </div>
            </div>
        </div>
    `
};
