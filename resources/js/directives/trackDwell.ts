import useAgent from "@/composables/useAgent";
import { useBlackboard } from "@/composables/useBlackboard";

export const trackDwell = {
    mounted(el: HTMLElement, binding: any) {
        const { addLog, addInterest } = useBlackboard();
        const { askAri } = useAgent();
        
        let timeout: ReturnType<typeof setTimeout>;
        let secondaryTimeout: ReturnType<typeof setTimeout>;
        let isHovering = false;
        
        const dwellTime = binding.value?.time || 500;
        const targetName = binding.value?.name || 'Desconocido';
        const techCategory = binding.value?.tech;

        function handleMouseEnter() {
            isHovering = true;
            timeout = setTimeout(async () => {
                addLog('HOVER_EVENT', `Analizando rápidamente: [${targetName}]...`);
                if (techCategory) addInterest(techCategory, 5);
                const isNewVisit = await askAri(targetName, techCategory);

                if (isNewVisit && isHovering) {
                    secondaryTimeout = setTimeout(() => {
                        if (isHovering) {
                            addLog('SYSTEM_HINT', `Si haces clic en [${targetName}], solicitaré su arquitectura técnica completa.`);
                        }
                    }, 3500);
                }
            }, dwellTime);
        };

        function handleMouseLeave() {
            isHovering = false;
            clearTimeout(timeout);
            clearTimeout(secondaryTimeout);
        };

        el.addEventListener('mouseenter', handleMouseEnter);
        el.addEventListener('mouseleave', handleMouseLeave);
        (el as any)._dwellHandlers = { handleMouseEnter, handleMouseLeave };
    },
    unmounted(el: HTMLElement) {
        const handlers = (el as any)._dwellHandlers;
        if (handlers) {
            el.removeEventListener('mouseenter', handlers.handleMouseEnter);
            el.removeEventListener('mouseleave', handlers.handleMouseLeave);
        }
    }
};