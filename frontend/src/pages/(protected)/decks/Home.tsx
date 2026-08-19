import api from "@/lib/api"
import { useEffect, useState } from "react"
import { Link } from "react-router-dom"

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
    <div>
      <Link to="/decks/create">Créer un deck</Link>
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
              <Link to={`/decks/${deck.id}`}>Voir</Link>
              <Link to={`/decks/edit/${deck.id}`}>Modifier</Link>
              <button onClick={() => removeDeck(deck.id)}>Supprimer</button>
              <hr />
            </li>
          ))}
        </ul>
      )}
    </div>
  )
}