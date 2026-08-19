import z from "zod"

export const createCardSchema = z.object({
  question: z.string().min(1, "Une question est requise"),
  answer: z.string().min(1, "Au moins une réponse est requise"),
})

export type CreateCardSchema = z.infer<typeof createCardSchema>
