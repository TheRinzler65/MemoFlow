import api from "@/lib/api"
import { useEffect, useState, type FormEvent } from "react"
import { useNavigate, useParams } from "react-router-dom"

interface Card {
  id: number
  question: string
  answer: string
  deck_id: number
}

export const EditCard = () => {
  const [question, setQuestion] = useState("")
  const [answer, setAnswer] = useState("")
  const [loading, setLoading] = useState<boolean>(true)
  const [error, setError] = useState<null | string>(null)

  const { id } = useParams()
  const navigate = useNavigate()

  useEffect(() => {
    const getCards = async () => {
      try {
        setLoading(true)
        const response = await api.get("/cards")
        if (response.data.status === "success") {
          const found = response.data.cards.find(
            (card: Card) => card.id === Number(id)
          )

          if (found) {
            setQuestion(found.question)
            setAnswer(found.answer)
          } else {
            setError("Carte introuvable")
          }
        } else {
          setError("Erreur")
        }
      } catch (error) {
        setError("Erreur lors de la récupération des cartes")
        console.error("Erreur lors de la récupération des cartes", error)
      } finally {
        setLoading(false)
      }
    }

    getCards()
  }, [id])

  const handleSubmit = async (event: FormEvent) => {
    event.preventDefault()

    try {
      const response = await api.put(`/cards/edit/${id}`, {
        question,
        answer,
      })

      if (response.data.status === "success") {
        navigate("/cards")
      } else {
        setError("Erreur")
      }
    } catch (error) {
      setError("Erreur lors de la modification de la carte")
      console.error("Erreur lors de la modification de la carte", error)
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
          <h1>Modifier la carte</h1>
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
            <button type="submit">Modifier</button>
          </form>
        </div>
      )}
    </div>
  )
}