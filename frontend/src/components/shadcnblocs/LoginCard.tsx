import { zodResolver } from "@hookform/resolvers/zod"
import { Controller, useForm } from "react-hook-form"

import { Button } from "@/components/ui/button"
import { Field, FieldError, FieldLabel } from "@/components/ui/field"
import { Input } from "@/components/ui/input"
import { useAuth } from "@/contexts/AuthContext"
import api from "@/lib/api"
import { loginSchema, type LoginSchema } from "@/lib/schemas/auth"
import { cn } from "@/lib/utils"
import { useNavigate } from "react-router-dom"
import { toast } from "sonner"

interface LoginProps {
  heading?: string
  logo: {
    url: string
    src: string
    alt: string
    title?: string
    className?: string
  }
  buttonText?: string
  signupText?: string
  signupUrl?: string
  signupUrlText?: string
  className?: string
}

const LoginCard = ({
  heading = "Connexion",
  logo = {
    url: "/",
    src: "/logo.svg",
    alt: "logo",
    title: "http://localhost:5173",
  },
  buttonText = "Se connecter",
  signupText = "Besoin d'un compte ?",
  signupUrl = "http://localhost:5173/sign-up",
  signupUrlText = "S'inscrire",
  className,
}: LoginProps) => {
  const navigate = useNavigate()
  const { login } = useAuth()

  const form = useForm<LoginSchema>({
    resolver: zodResolver(loginSchema),
    defaultValues: {
      email: "",
      password: "",
    },
  })

  async function onSubmit(data: LoginSchema) {
    const safeData = loginSchema.safeParse(data)

    if (!safeData.success) {
      console.log("Erreur")
      return;
    }

    try {
      const res = await api.post("/login", {
        email: safeData.data?.email,
        password: safeData.data?.password,
      })

      if (res.data.status === "success") {
        login(res.data.user)
        toast.success("Vous êtes connecté !")
        navigate("/dashboard", { replace: true })
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
                    autoComplete="current-password"
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
          <div className="flex justify-center gap-1 text-sm">
            <p>{signupText}</p>
            <a href={signupUrl} className="font-medium hover:underline">
              {signupUrlText}
            </a>
          </div>
        </div>
      </div>
    </section>
  )
}

export { LoginCard }
