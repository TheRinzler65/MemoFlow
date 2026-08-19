import api from "@/lib/api"
import { useEffect, useState, type FormEvent } from "react"
import { useNavigate, useParams } from "react-router-dom"

interface Deck {
  id: number
  title: string
  description: string
  created_at: string
}

export const EditDeck = () => {
  const [deck, setDeck] = useState<Deck | null>(null)
  const [title, setTitle] = useState("")
  const [description, setDescription] = useState("")
  const [loading, setLoading] = useState<boolean>(true)
  const [error, setError] = useState<null | string>(null)

  const { id } = useParams()
  const navigate = useNavigate()

  useEffect(() => {
    const getDeck = async () => {
      try {
        setLoading(true)
        const response = await api.get(`/decks/show/${id}`)
        if (response.data.status === "success") {
          setDeck(response.data.deck)
          setTitle(response.data.deck.title)
          setDescription(response.data.deck.description)
        } else {
          setError("Erreur")
        }
      } catch (error) {
        setError("Erreur lors de la récupération du deck")
        console.error("Erreur lors de la récupération du deck", error)
      } finally {
        setLoading(false)
      }
    }

    getDeck()
  }, [id])

  const handleSubmit = async (event: FormEvent) => {
    event.preventDefault()

    try {
      const response = await api.put(`/decks/edit/${id}`, {
        title,
        description,
      })

      if (response.data.status === "success") {
        navigate("/decks")
      } else {
        setError("Erreur")
      }
    } catch (error) {
      setError("Erreur lors de la modification du deck")
      console.error("Erreur lors de la modification du deck", error)
    }
  }

  return (
    <div>
      {loading ? (
        "Chargement..."
      ) : error ? (
        error
      ) : (
        <div>
          <h1>Modifier le deck</h1>
          {deck && (
            <form onSubmit={handleSubmit}>
              <label>
                Titre :
                <input type="text" value={title} onChange={(e) => setTitle(e.target.value)} />
              </label>
              <br />
              <label>
                Description :
                <input type="text" value={description} onChange={(e) => setDescription(e.target.value)} />
              </label>
              <br />
              <button type="submit">Modifier</button>
            </form>
          )}
        </div>
      )}
    </div>
  )
}