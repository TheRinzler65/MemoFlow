import { useAuth } from "@/contexts/AuthContext"

export const HomeDashboard = () => {
  const { user, logout } = useAuth();

  return (
    <div>
      <p>Bienvenue, {user?.name}</p>
      <button onClick={() => logout()}>Se déconnecter</button>
    </div>
  )
}
