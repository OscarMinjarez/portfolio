import { useBlackboard } from "@/composables/useBlackboard";

export const trackFocus = {
    mounted(el: HTMLElement, binding: any) {
        const { setFocus } = useBlackboard();
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const sectionName = binding.value.name;
                    setFocus(sectionName);
                    console.log(`[Observer] Viendo: ${sectionName}`);
                }
            });
        }, {
            threshold: 0.6
        });
        observer.observe(el);
        (el as any)._focusObserver = observer;
    },
    unmounted(el: HTMLElement) {
        if ((el as any)._focusObserver) {
            (el as any)._focusObserver.disconnect();
        }
    }
};
