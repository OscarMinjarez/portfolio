import axios from "axios";
import { useBlackboard } from "@composables/useBlackboard";

const visitedNodes = new Set<string>();

export default function useAgent() {
    const { addLog } = useBlackboard();

    async function askAri(focus: string, tech: string = "general") {
        if (visitedNodes.has(focus)) {
            const revisitPhrases = [
                `Ya le dimos una vuelta a **${focus}**, pero si quieres ver cómo están los fierros por dentro, dale clic.`,
                `Si te quedaron dudas con **${focus}**, pícale ahí para que veas el reporte completo.`,
                `Ya sabes qué onda con **${focus}**. Dale clic a la tarjeta si quieres ver el desglose técnico de a de veras.`,
                `¿Le quieres escarbar más a **${focus}**? Pícale para ver todo el código que se aventó Oscar.`,
                `Ahí está lo básico de **${focus}**. Si quieres ver la arquitectura completa, dale clic sin miedo.`,
                `Ya checamos **${focus}** por encima. Entra para ver el detalle técnico real de **${focus}**.`,
                `Si te late el stack de **${focus}**, dale clic para ver qué más trae Oscar ahí guardado.`,
                `Ya pasamos por **${focus}**. Dale una leída al reporte completo dándole clic a la tarjeta.`,
                `¿Quieres ver cómo está armado **${focus}**? Pícale para abrir el despliegue técnico.`,
                `Se ve chilo **${focus}**, ¿no? Dale clic si quieres ver los detalles del stack completo.`,
                `Ya le echamos el ojo a **${focus}**. Si quieres ver el reporte técnico macizo, dale clic.`,
                `Si quieres ver qué más trae Oscar en **${focus}**, pícale a la tarjeta para abrir el detalle.`,
                `Ya viste el resumen de **${focus}**. Dale clic si quieres ver el jale completo que hizo Oscar.`,
                `¿Te interesa **${focus}**? Pícale ahí para ver el reporte de arquitectura y todos los fierros.`,
                `Ya analizamos **${focus}**. Dale clic para que veas cómo está aterrizada la idea técnicamente.`,
                `Si quieres profundizar en **${focus}**, pícale a la tarjeta para revisar el detalle técnico.`,
                `Ya sabes qué trae **${focus}**, pero si quieres ver el desglose completo, dale clic.`,
                `¿Le vas a seguir con **${focus}**? Pícale para ver el reporte de arquitectura completo.`,
                `Ya le dimos el visto bueno a **${focus}**. Entra para ver los detalles técnicos más a fondo.`,
                `Si quieres ver el jale de Oscar en **${focus}** al cien, pícale a la tarjeta.`,
                `Ya pasaste por **${focus}**. Dale clic si quieres ver cómo está el rollo técnico por dentro.`,
                `¿Quieres ver los fierros de **${focus}**? Pícale a la tarjeta para el detalle completo.`,
                `Ya le dimos una calada a **${focus}**. Si quieres ver el reporte de verdad, dale clic.`,
                `¿Te llamó la atención **${focus}**? Pícale para ver cómo Oscar resolvió la lógica ahí.`,
                `Ya anduvimos por **${focus}**. Dale clic si quieres ver el reporte técnico sin filtros.`
            ];
            const randomPhrase = revisitPhrases[Math.floor(Math.random() * revisitPhrases.length)];            
            addLog('AGENT_ARI', randomPhrase);
            return; 
        }
        visitedNodes.add(focus);
        try {
            const response = await axios.post("/api/agent/insight", {
                context: {
                    focus, tech
                }
            });
            if (response.data.insight) {
                addLog('AGENT_ARI', response.data.insight);
            }
        } catch (error) {
            addLog('SYSTEM_ERROR', 'Fallo en la conexión neuronal con el backend.');
            console.error('[Agent API Error]:', error);
        }
    }

    return {
        askAri
    };
}