import { BrowserRouter, Route, Routes } from "react-router-dom"
import ProtectedRoute from "./components/ProtectedRoute"
import { HomeDashboard } from "./pages/(protected)/dashboard/Home"
import { Home } from "./pages/(public)/Home"
import { Login } from "./pages/(public)/Login"
import { Register } from "./pages/(public)/Register"
import { HomeDecks } from "./pages/(protected)/decks/Home"
import { ShowDeck } from "./pages/(protected)/decks/Show"

export function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/sign-in" element={<Login />} />
        <Route path="/sign-up" element={<Register />} />

        <Route
          path="/dashboard"
          element={
            <ProtectedRoute>
              <HomeDashboard />
            </ProtectedRoute>
          }
        />
        <Route
          path="/decks"
          element={
            <ProtectedRoute>
              <HomeDecks />
            </ProtectedRoute>
          }
        />
        <Route
          path="/decks/:id"
          element={
            <ProtectedRoute>
              <ShowDeck />
            </ProtectedRoute>
          }
        />
      </Routes>
    </BrowserRouter>
  )
}

export default App
