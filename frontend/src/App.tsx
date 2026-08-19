import { BrowserRouter, Route, Routes } from "react-router-dom"
import ProtectedRoute from "./components/ProtectedRoute"
import { HomeDashboard } from "./pages/(protected)/dashboard/Home"
import { Home } from "./pages/(public)/Home"
import { Login } from "./pages/(public)/Login"
import { Register } from "./pages/(public)/Register"
import { HomeDecks } from "./pages/(protected)/decks/Home"
import { ShowDeck } from "./pages/(protected)/decks/Show"
import { CreateDeck } from "./pages/(protected)/decks/Create"
import { EditDeck } from "./pages/(protected)/decks/Edit"
import { HomeCards } from "./pages/(protected)/cards/Home"
import { ShowCard } from "./pages/(protected)/cards/Deck"
import { CreateCard } from "./pages/(protected)/cards/Create"
import { EditCard } from "./pages/(protected)/cards/Edit"
import { HomeReview } from "./pages/(protected)/reviews/Home"

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
        <Route
          path="/decks/create"
          element={
            <ProtectedRoute>
              <CreateDeck />
            </ProtectedRoute>
          }
        />
        <Route
          path="/decks/edit/:id"
          element={
            <ProtectedRoute>
              <EditDeck />
            </ProtectedRoute>
          }
        />
        <Route
          path="/cards"
          element={
            <ProtectedRoute>
              <HomeCards />
            </ProtectedRoute>
          }
        />
        <Route
          path="/cards/create"
          element={
            <ProtectedRoute>
              <CreateCard />
            </ProtectedRoute>
          }
        />
        <Route
          path="/cards/edit/:id"
          element={
            <ProtectedRoute>
              <EditCard />
            </ProtectedRoute>
          }
        />
        <Route
          path="/cards/:id"
          element={
            <ProtectedRoute>
              <ShowCard />
            </ProtectedRoute>
          }
        />
        <Route
          path="/reviews"
          element={
            <ProtectedRoute>
              <HomeReview />
            </ProtectedRoute>
          }
        />
      </Routes>
    </BrowserRouter>
  )
}

export default App
