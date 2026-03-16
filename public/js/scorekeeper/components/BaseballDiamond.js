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
                <div class="absolute bottom-0 right-0 w-12 h-12 -mr-6 -mb-6 rotate-45 bg-white shadow-lg flex items-center justify-center transition-all duration-300"
                     :class="{'ring-4 ring-amber-400 bg-amber-100': gameState?.bases?.first}">
                     <div v-if="gameState?.bases?.first" class="transform -rotate-45 flex flex-col items-center justify-center text-[10px] leading-tight text-slate-800">
                        <span class="font-bold underline">1B</span>
                        <span class="font-black uppercase truncate max-w-[40px]">{{ gameState.bases.first.last_name }}</span>
                        <span class="opacity-70">({{ gameState.bases.first.position }})</span>
                     </div>
                </div>
                
                <!-- Segunda Base (Arriba) -->
                <div class="absolute top-0 right-0 w-12 h-12 -mr-6 -mt-6 rotate-45 bg-white shadow-lg flex items-center justify-center transition-all duration-300"
                     :class="{'ring-4 ring-amber-400 bg-amber-100': gameState?.bases?.second}">
                     <div v-if="gameState?.bases?.second" class="transform -rotate-45 flex flex-col items-center justify-center text-[10px] leading-tight text-slate-800">
                        <span class="font-bold underline">2B</span>
                        <span class="font-black uppercase truncate max-w-[40px]">{{ gameState.bases.second.last_name }}</span>
                        <span class="opacity-70">({{ gameState.bases.second.position }})</span>
                     </div>
                </div>
                
                <!-- Tercera Base (Izquierda) -->
                <div class="absolute top-0 left-0 w-12 h-12 -ml-6 -mt-6 rotate-45 bg-white shadow-lg flex items-center justify-center transition-all duration-300"
                     :class="{'ring-4 ring-red-500 bg-red-100': gameState?.bases?.third}">
                     <div v-if="gameState?.bases?.third" class="transform -rotate-45 flex flex-col items-center justify-center text-[10px] leading-tight text-slate-800">
                        <span class="font-bold underline">3B</span>
                        <span class="font-black uppercase truncate max-w-[40px]">{{ gameState.bases.third.last_name }}</span>
                        <span class="opacity-70">({{ gameState.bases.third.position }})</span>
                     </div>
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
