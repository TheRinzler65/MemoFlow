import api from "@/lib/api"
import { useEffect, useState, type FormEvent } from "react"
import { useNavigate } from "react-router-dom"

interface Deck {
  id: number
  title: string
}

export const CreateCard = () => {
  const [decks, setDecks] = useState<Deck[]>([])
  const [question, setQuestion] = useState("")
  const [answer, setAnswer] = useState("")
  const [deckId, setDeckId] = useState("")
  const [error, setError] = useState<null | string>(null)

  const navigate = useNavigate()

  useEffect(() => {
    const getDecks = async () => {
      try {
        const response = await api.get("/decks")
        if (response.data.status === "success") {
          setDecks(response.data.decks)
        } else {
          setError("Erreur")
        }
      } catch (error) {
        setError("Erreur lors de la récupération des decks")
        console.error("Erreur lors de la récupération des decks", error)
      }
    }

    getDecks()
  }, [])

  const handleSubmit = async (event: FormEvent) => {
    event.preventDefault()

    try {
      const response = await api.post("/cards/create", {
        question,
        answer,
        deck_id: deckId,
      })

      if (response.data.status === "success") {
        navigate("/cards")
      } else {
        setError("Erreur")
      }
    } catch (error) {
      setError("Erreur lors de la création de la carte")
      console.error("Erreur lors de la création de la carte", error)
    }
  }

  return (
    <div>
      <h1>Créer une carte</h1>
      {error && <p>{error}</p>}
      <form onSubmit={handleSubmit}>
        <label>
          Question :
          <input type="text" value={question} onChange={(e) => setQuestion(e.target.value)} />
        </label>
        <br />
        <label>
          Réponse :
          <input type="text" value={answer} onChange={(e) => setAnswer(e.target.value)} />
        </label>
        <br />
        <label>
          Deck :
          <select value={deckId} onChange={(e) => setDeckId(e.target.value)}>
            <option value="">Choisir un deck</option>
            {decks.map((deck) => (
              <option key={deck.id} value={deck.id}>
                {deck.title}
              </option>
            ))}
          </select>
        </label>
        <br />
        <button type="submit">Créer</button>
      </form>
    </div>
  )
}