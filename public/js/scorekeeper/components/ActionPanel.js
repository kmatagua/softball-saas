import { ref } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';

export default {
    name: 'ActionPanel',
    emits: ['record-event'],
    setup(props, { emit }) {
        const isSubmitting = ref(false);

        const recordEvent = async (eventType) => {
            if (isSubmitting.value) return;
            isSubmitting.value = true;
            try {
                // Notifica al padre para que procese el request a la API
                await emit('record-event', eventType);
            } finally {
                isSubmitting.value = false;
            }
        };

        return { recordEvent, isSubmitting };
    },
    template: `
        <div class="flex flex-col gap-4">
            
            <!-- Pitcheos Basicos -->
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wider mb-2 font-bold">Pitcheo</p>
                <div class="grid grid-cols-2 gap-3">
                    <button @click="recordEvent('strike')" :disabled="isSubmitting" class="py-3 px-4 bg-red-500/10 text-red-400 border border-red-500/30 rounded-lg hover:bg-red-500/20 font-bold transition-colors disabled:opacity-50">Strike</button>
                    <button @click="recordEvent('ball')" :disabled="isSubmitting" class="py-3 px-4 bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 rounded-lg hover:bg-emerald-500/20 font-bold transition-colors disabled:opacity-50">Bola</button>
                    <button @click="recordEvent('foul')" :disabled="isSubmitting" class="py-3 px-4 bg-amber-500/10 text-amber-400 border border-amber-500/30 rounded-lg hover:bg-amber-500/20 font-bold col-span-2 transition-colors disabled:opacity-50">Foul</button>
                </div>
            </div>

            <hr class="border-dark-border my-2">

            <!-- En Juego (Hits y Outs) -->
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wider mb-2 font-bold">En Juego: Bateo</p>
                <div class="grid grid-cols-2 gap-3">
                    <button @click="recordEvent('single')" :disabled="isSubmitting" class="py-3 px-4 bg-brand-600/20 text-brand-300 border border-brand-500/30 rounded-lg hover:bg-brand-600/40 font-bold transition-colors disabled:opacity-50">Sencillo (1B)</button>
                    <button @click="recordEvent('double')" :disabled="isSubmitting" class="py-3 px-4 bg-brand-600/20 text-brand-300 border border-brand-500/30 rounded-lg hover:bg-brand-600/40 font-bold transition-colors disabled:opacity-50">Doble (2B)</button>
                    <button @click="recordEvent('triple')" :disabled="isSubmitting" class="py-3 px-4 bg-brand-600/20 text-brand-300 border border-brand-500/30 rounded-lg hover:bg-brand-600/40 font-bold transition-colors disabled:opacity-50">Triple (3B)</button>
                    <button @click="recordEvent('homerun')" :disabled="isSubmitting" class="py-3 px-4 bg-purple-500/20 text-purple-300 border border-purple-500/30 rounded-lg hover:bg-purple-500/40 font-bold transition-colors shadow-[0_0_15px_rgba(168,85,247,0.2)] disabled:opacity-50">Home Run!</button>
                </div>
            </div>

            <hr class="border-dark-border my-2">

            <!-- Outs de Jugada -->
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wider mb-2 font-bold">En Juego: Defensa</p>
                <div class="grid grid-cols-1 gap-3">
                    <button @click="recordEvent('out')" :disabled="isSubmitting" class="py-3 px-4 bg-slate-700/50 text-white border border-slate-600 rounded-lg hover:bg-slate-600 font-bold transition-colors disabled:opacity-50">Out en Jugada</button>
                    <button @click="recordEvent('double_play')" :disabled="isSubmitting" class="py-3 px-4 bg-slate-700/50 text-amber-200 border border-slate-600 rounded-lg hover:bg-slate-600 font-bold transition-colors disabled:opacity-50">Doble Play</button>
                </div>
            </div>

        </div>
    `
};
