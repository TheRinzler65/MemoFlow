import { type ReactNode } from "react"
import { Navigate, useLocation } from "react-router-dom"

export default function ProtectedRoute({ children }: { children: ReactNode }) {
  const isAuthenticated = false // temporaire
  const location = useLocation()

  if (!isAuthenticated) {
    return (
      <Navigate
        to="/sign-in"
        replace
        state={{ from: location.pathname, error: "auth_required" }}
      />
    )
  }

  return children
}
