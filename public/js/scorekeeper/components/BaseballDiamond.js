import { ref } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';

export default {
    name: 'BaseballDiamond',
    props: {
        gameState: {
            type: Object,
            required: true
        }
    },
    setup(props) {
        // En base al game state, calculamos quién está en base
        // Supongamos que vendrá en props.gameState.bases = { first: null, second: null, third: null }
        return {};
    },
    template: `
        <div class="relative w-full max-w-sm mx-auto aspect-square bg-emerald-700/20 rounded-xl p-8 border-2 border-emerald-500/30 flex items-center justify-center shadow-inner overflow-hidden">
            <!-- Patrón de grama / dirt (opcional visualmente) -->
            <div class="absolute inset-4 border border-emerald-500/20 rotate-45 transform"></div>
            
            <!-- Diamante rotado -->
            <div class="relative w-full h-full transform -rotate-45">
                <!-- Home Plate (Abajo) -->
                <div class="absolute bottom-0 left-0 w-8 h-8 -ml-4 -mb-4 rotate-45 flex items-center justify-center">
                    <svg class="w-full h-full text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L2 12V22H22V12L12 2Z"/>
                    </svg>
                </div>
                
                <!-- Primera Base (Derecha) -->
                <div class="absolute bottom-0 right-0 w-8 h-8 -mr-4 -mb-4 rotate-45 bg-white shadow-lg flex items-center justify-center transition-all duration-300"
                     :class="{'ring-4 ring-amber-400 bg-amber-100': gameState?.bases?.first}">
                     <span v-if="gameState?.bases?.first" class="text-xs font-bold text-slate-800 transform -rotate-45 text-center leading-tight">1B</span>
                </div>
                
                <!-- Segunda Base (Arriba) -->
                <div class="absolute top-0 right-0 w-8 h-8 -mr-4 -mt-4 rotate-45 bg-white shadow-lg flex items-center justify-center transition-all duration-300"
                     :class="{'ring-4 ring-amber-400 bg-amber-100': gameState?.bases?.second}">
                     <span v-if="gameState?.bases?.second" class="text-xs font-bold text-slate-800 transform -rotate-45 text-center leading-tight">2B</span>
                </div>
                
                <!-- Tercera Base (Izquierda) -->
                <div class="absolute top-0 left-0 w-8 h-8 -ml-4 -mt-4 rotate-45 bg-white shadow-lg flex items-center justify-center transition-all duration-300"
                     :class="{'ring-4 ring-red-500 bg-red-100': gameState?.bases?.third}">
                     <span v-if="gameState?.bases?.third" class="text-xs font-bold text-slate-800 transform -rotate-45 text-center leading-tight shadow-sm">3B</span>
                </div>

                <!-- Líneas de foul y cuadro interior -->
                <div class="absolute top-0 right-0 bottom-0 left-0 border-r-2 border-b-2 border-white/40"></div>
                <div class="absolute inset-0 bg-amber-900/30"></div> <!-- Infield dirt -->
            </div>
            
            <!-- Pitcher's Mound -->
            <div class="absolute w-12 h-12 rounded-full bg-amber-800/60 border border-amber-700/50 flex items-center justify-center transform -translate-y-2">
                <div class="w-6 h-1.5 bg-white/80 rounded-sm"></div>
            </div>
        </div>
    `
};
