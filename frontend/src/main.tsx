import { createRoot } from "react-dom/client"
import { ThemeProvider } from "@/components/theme-provider.tsx"
import { Toaster } from "sonner"
import App from "./App.tsx"
import "./index.css"
import { AuthProvider } from "./contexts/AuthContext.tsx"

createRoot(document.getElementById("root")!).render(
  <ThemeProvider>
    <AuthProvider>
    <App />
    <Toaster />
    </AuthProvider>
  </ThemeProvider>
)
