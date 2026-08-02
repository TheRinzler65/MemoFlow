import { zodResolver } from "@hookform/resolvers/zod"
import { Controller, useForm } from "react-hook-form"

import { Button } from "@/components/ui/button"
import { Field, FieldError, FieldLabel } from "@/components/ui/field"
import { Input } from "@/components/ui/input"
import api from "@/lib/api"
import { registerSchema, type RegisterSchema } from "@/lib/schemas/auth"
import { cn } from "@/lib/utils"
import { useNavigate } from "react-router-dom"
import { toast } from "sonner"

interface SignupProps {
  heading?: string
  logo: {
    url: string
    src: string
    alt: string
    title?: string
  }
  buttonText?: string
  signupText?: string
  signupUrl?: string
  signupUrlText?: string
  className?: string
}

const RegisterCard = ({
  heading = "S'inscrire",
  logo = {
    url: "/",
    src: "/logo.svg",
    alt: "Logo de MemoFlow",
    title: "Logo de MemoFlow",
  },
  buttonText = "Créer un compte",
  signupText = "Vous avez déjà un compte ?",
  signupUrl = "/sign-in",
  signupUrlText = "Se connecter",
  className,
}: SignupProps) => {
  const navigate = useNavigate()

  const form = useForm<RegisterSchema>({
    resolver: zodResolver(registerSchema),
    defaultValues: {
      name: "",
      email: "",
      password: "",
      confirmPassword: "",
    },
  })

  async function onSubmit(data: RegisterSchema) {
    const safeData = registerSchema.safeParse(data)

    if (!safeData.success) {
      toast.error("Données invalides")
      return
    }

    try {
      const res = await api.post("/register", {
        name: safeData.data?.name,
        email: safeData.data?.email,
        password: safeData.data?.password,
        confirm: safeData.data?.confirmPassword,
      })

      if (res.data.status === "success") {
        navigate("/sign-in", {
          replace: true,
          state: { from: location.pathname, success: "register_success" },
        })
      }
    } catch (error) {
      toast.error("Something went wrong.")
    }
  }

  return (
    <section className={cn("h-screen bg-muted", className)}>
      <div className="flex h-full items-center justify-center">
        {/* Logo */}
        <div className="flex flex-col items-center gap-6 lg:justify-start">
          <a href={logo.url}>
            <img
              src={logo.src}
              alt={logo.alt}
              title={logo.title}
              className="h-10 dark:invert"
            />
          </a>
          <form
            onSubmit={form.handleSubmit(onSubmit)}
            noValidate
            className="flex w-full flex-col items-center gap-y-4 rounded-md border border-muted bg-background px-6 py-8 shadow-md sm:mx-0 sm:w-sm"
          >
            {heading && <h1 className="text-xl font-semibold">{heading}</h1>}
            <Controller
              name="name"
              control={form.control}
              render={({ field, fieldState }) => (
                <Field data-invalid={fieldState.invalid}>
                  <FieldLabel htmlFor={field.name}>Nom</FieldLabel>
                  <Input
                    {...field}
                    id={field.name}
                    placeholder="Jean"
                    className="text-sm"
                    aria-invalid={fieldState.invalid}
                  />
                  {fieldState.invalid && (
                    <FieldError errors={[fieldState.error]} />
                  )}
                </Field>
              )}
            />

            <Controller
              name="email"
              control={form.control}
              render={({ field, fieldState }) => (
                <Field data-invalid={fieldState.invalid}>
                  <FieldLabel htmlFor={field.name}>Email</FieldLabel>
                  <Input
                    {...field}
                    id={field.name}
                    type="email"
                    placeholder="Email"
                    className="text-sm"
                    autoComplete="email"
                    aria-invalid={fieldState.invalid}
                  />
                  {fieldState.invalid && (
                    <FieldError errors={[fieldState.error]} />
                  )}
                </Field>
              )}
            />
            <Controller
              name="password"
              control={form.control}
              render={({ field, fieldState }) => (
                <Field data-invalid={fieldState.invalid}>
                  <FieldLabel htmlFor={field.name}>Mot de passe</FieldLabel>
                  <Input
                    {...field}
                    id={field.name}
                    type="password"
                    placeholder="Mot de passe"
                    className="text-sm"
                    autoComplete="new-password"
                    aria-invalid={fieldState.invalid}
                  />
                  {fieldState.invalid && (
                    <FieldError errors={[fieldState.error]} />
                  )}
                </Field>
              )}
            />
            <Controller
              name="confirmPassword"
              control={form.control}
              render={({ field, fieldState }) => (
                <Field data-invalid={fieldState.invalid}>
                  <FieldLabel htmlFor={field.name}>
                    Confirmation du mot de passe
                  </FieldLabel>
                  <Input
                    {...field}
                    id={field.name}
                    type="password"
                    placeholder="Confirmation du mot de passe"
                    className="text-sm"
                    autoComplete="new-password"
                    aria-invalid={fieldState.invalid}
                  />
                  {fieldState.invalid && (
                    <FieldError errors={[fieldState.error]} />
                  )}
                </Field>
              )}
            />
            <Button type="submit" className="w-full">
              {buttonText}
            </Button>
          </form>
          <div className="flex justify-center gap-1 text-sm text-muted-foreground">
            <p>{signupText}</p>
            <a
              href={signupUrl}
              className="font-medium text-primary hover:underline"
            >
              {signupUrlText}
            </a>
          </div>
        </div>
      </div>
    </section>
  )
}

export { RegisterCard }
