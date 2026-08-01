import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { cn } from "@/lib/utils"

interface SignupProps {
  heading?: string
  logo: {
    url: string
    src: string
    alt: string
    title?: string
  }
  buttonText?: string
  googleText?: string
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
          <div className="flex w-full flex-col items-center gap-y-4 rounded-md border border-muted bg-background px-6 py-8 shadow-md sm:mx-0 sm:w-sm">
            {heading && <h1 className="text-xl font-semibold">{heading}</h1>}
            <Input
              type="email"
              placeholder="Email"
              className="text-sm"
              required
            />
            <Input
              type="password"
              placeholder="Mot de passe"
              className="text-sm"
              required
            />
            <Input
              type="password"
              placeholder="Confirmation du mot de passe"
              className="text-sm"
              required
            />
            <Button type="submit" className="w-full">
              {buttonText}
            </Button>
          </div>
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
