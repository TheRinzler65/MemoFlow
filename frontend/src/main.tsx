import { ThemeProvider } from "@/components/theme-provider.tsx"
import { createRoot } from "react-dom/client"
import { Toaster } from "sonner"
import App from "./App.tsx"
import { AuthProvider } from "./contexts/AuthContext.tsx"
import "./index.css"

createRoot(document.getElementById("root")!).render(
  <ThemeProvider>
    <AuthProvider> 
      {/* Beneficie du context */}
      <App />
      <Toaster />
    </AuthProvider>
  </ThemeProvider>
)
