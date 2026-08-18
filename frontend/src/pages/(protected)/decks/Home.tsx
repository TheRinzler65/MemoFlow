import api from "@/lib/api"
import { useEffect, useState } from "react"

interface Decks {
  id: number
  title: string
  description: string
  created_at: string
}

export const HomeDecks = () => {
  const [decks, setDecks] = useState<Decks[]>([])
  const [loading, setLoading] = useState<boolean>(true)
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

  return (
    <div>
      {loading ? (
        "Chargement..."
      ) : error ? (
        error
      ) : (
        <ul>
          {decks.map((deck) => (
            <li key={deck.id}>
              <h1>Titre : {deck.title}</h1>
              <p>Description : {deck.description}</p>
              <p>Crée le :{deck.created_at}</p>
              <p>ID : {deck.id}</p>
            </li>
          ))}
        </ul>
      )}
    </div>
  )
}
