import { z } from "zod"

export const loginSchema = z.object({
  email: z
    .email("Veuillez saisir une adresse email valide.")
    .min(1, "L'email est requis."),
  password: z
    .string()
    .min(6, "Le mot de passe doit contenir au moins 6 caractères."),
  remember: z.boolean().optional(),
})

export type LoginSchema = z.infer<typeof loginSchema>

export const registerSchema = z
  .object({
    name: z.string().min(3, "Le nom doit contenir au moins 3 caractères."),
    email: z
      .email("Veuillez saisir une adresse email valide.")
      .min(1, "L'email est requis."),
    password: z
      .string()
      .min(8, "Le mot de passe doit contenir au moins 8 caractères."),
    confirmPassword: z
      .string()
      .min(1, "La confirmation du mot de passe est requise."),
  })
  .refine((data) => data.password === data.confirmPassword, {
    message: "Les mots de passe ne correspondent pas.",
    path: ["confirmPassword"],
  })

export type RegisterSchema = z.infer<typeof registerSchema>
