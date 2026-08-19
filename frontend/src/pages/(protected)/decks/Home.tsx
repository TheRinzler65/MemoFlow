import { DeckTable } from "@/components/decks/DeckTable"
import { buttonVariants } from "@/components/ui/button"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import api from "@/lib/api"
import type { Deck } from "@/types/Deck"
import { LayoutGrid, LayoutList } from "lucide-react"
import { useEffect, useState } from "react"
import { Link } from "react-router-dom"

export const HomeDecks = () => {
  const [decks, setDecks] = useState<Deck[]>([])
  const [loading, setLoading] = useState<boolean>(false)
  const [error, setError] = useState<null | string>(null)

  useEffect(() => {
    const getDecks = async () => {
      try {
        setLoading(true)
        const response = await api.get("/decks")
        if (response.data.status === "success") {
          setDecks(response.data.decks)
        } else {
          setError("Erreur")
        }
      } catch (error) {
        setError("Erreur lors de la récupération des decks")
        console.error("Erreur lors de la récupération des decks", error)
      } finally {
        setLoading(false)
      }
    }

    getDecks()
  }, [])

  const removeDeck = async (id: number) => {
    if (!confirm("Supprimer ce deck ?")) {
      return
    }

    try {
      const response = await api.delete(`/decks/remove/${id}`)
      if (response.data.status === "success") {
        setDecks(decks.filter((deck) => deck.id !== id))
      } else {
        setError("Erreur")
      }
    } catch (error) {
      setError("Erreur lors de la suppression du deck")
      console.error("Erreur lors de la suppression du deck", error)
    }
  }

  return (
    <div className="h-screen">
      <Link to="/decks/create" className={buttonVariants()}>
        Créer un deck
      </Link>
      {loading ? (
        "Chargement..."
      ) : error ? (
        error
      ) : (
        <Tabs defaultValue="overview" className="h-full">
          <TabsList>
            <TabsTrigger value="list">
              <LayoutList />
            </TabsTrigger>
            <TabsTrigger value="grid">
              <LayoutGrid />
            </TabsTrigger>
          </TabsList>
          <TabsContent value="list">
            <div className="flex h-full w-full items-center justify-center">
              <DeckTable decks={decks} removeDeck={removeDeck}/>
            </div>
          </TabsContent>
          <TabsContent value="grid">grid</TabsContent>
        </Tabs>
      )}
    </div>
  )
}
