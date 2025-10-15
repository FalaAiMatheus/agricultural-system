import { z } from "zod";

const envVariables = z.object({
  VITE_API_URL: z
    .url({ error: "URL invalida" })
    .min(1, "Url da API é obrigatória"),
});

export const env = envVariables.safeParse(import.meta.env);
