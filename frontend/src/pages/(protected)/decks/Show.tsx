import api from "@/lib/api"
import { useEffect, useState } from "react"
import { useParams } from "react-router-dom"

interface Deck {
  id: number
  title: string
  description: string
  created_at: string
}

export const ShowDeck = () => {
  const [deck, setDeck] = useState<Deck | null>()
  const [loading, setLoading] = useState<boolean>(true)
  const [error, setError] = useState<null | string>(null)

  const { id } = useParams()

  useEffect(() => {
    const getDeck = async () => {
      try {
        setLoading(true)
        const response = await api.get(`/decks/show/${id}`)
        if (response.data.status === "success") {
          setDeck(response.data.deck)
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

    getDeck()
  }, [id])

  return (
    <div>
      {loading ? (
        "Chargement..."
      ) : error ? (
        error
      ) : (
        <ul>
          {deck && (
            <li key={deck.id}>
              <h1>Titre : {deck.title}</h1>
              <p>Description : {deck.description}</p>
              <p>Crée le :{deck.created_at}</p>
              <p>ID : {deck.id}</p>
            </li>
          )}
        </ul>
      )}
    </div>
  )
}
