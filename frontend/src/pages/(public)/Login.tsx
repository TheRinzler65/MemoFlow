import { LoginCard } from "@/components/shadcnblocs/LoginCard"
import { useEffect } from "react"
import { useLocation } from "react-router-dom"
import { toast } from "sonner"

export const Login = () => {
  const location = useLocation()

  useEffect(() => {
    if (location.state?.error === "auth_required") {
      toast.error("Vous devez être connecté")
    }

    if (location.state?.success === "register_success") {
      toast.success("Compte créé avec succès !")
    }
  }, [location])

  return (
    <div>
      <LoginCard
        logo={{
          title: "Test",
          src: "/logo.svg",
          url: "/",
          alt: "Logo de MemoFlow",
        }}
      />
    </div>
  )
}
