import z from "zod"

export const createDeckSchema = z.object({
  title: z.string().min(1, "Le champ titre est requis"),
  description: z
    .string()
    .trim()
    .optional()
    .refine(
      (val) => !val || val.length >= 10,
      "Si renseignée, la description doit comporter au moins 10 caractères."
    ),
})

export type CreateDeckSchema = z.infer<typeof createDeckSchema>
