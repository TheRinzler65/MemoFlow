import api from "@/lib/api"
import { useEffect, useState } from "react"

interface Card {
  id: number
  question: string
  answer: string
  box: number
  next_review: string
  last_review: string
  deck_id: number
}

export const HomeReview = () => {
  const [cards, setCards] = useState<Card[]>([])
  const [currentIndex, setCurrentIndex] = useState<number>(0)
  const [showAnswer, setShowAnswer] = useState<boolean>(false)
  const [sessionDone, setSessionDone] = useState<boolean>(false)
  const [loading, setLoading] = useState<boolean>(true)
  const [error, setError] = useState<null | string>(null)

  useEffect(() => {
    const getCards = async () => {
      try {
        setLoading(true)
        const response = await api.get("/reviews/today")
        if (response.data.status === "success") {
          setCards(response.data.cards)
        } else {
          setError("Erreur")
        }
      } catch (error) {
        setError("Erreur lors de la récupération des cartes à réviser")
        console.error("Erreur lors de la récupération des cartes à réviser", error)
      } finally {
        setLoading(false)
      }
    }

    getCards()
  }, [])

  const submitAnswer = async (success: boolean) => {
    const card = cards[currentIndex]

    try {
      await api.post(`/reviews/${card.id}`, { success })

      const nextIndex = currentIndex + 1

      if (nextIndex >= cards.length) {
        setSessionDone(true)
      } else {
        setCurrentIndex(nextIndex)
        setShowAnswer(false)
      }
    } catch (error) {
      setError("Erreur lors de l'envoi de la réponse")
      console.error("Erreur lors de l'envoi de la réponse", error)
    }
  }

  const stopSession = () => {
    setSessionDone(true)
  }

  const card = cards[currentIndex]

  return (
    <div>
      {loading ? (
        "Chargement..."
      ) : error ? (
        error
      ) : sessionDone ? (
        <div>
          <h1>Session terminée</h1>
          <p>Il reste {cards.length - currentIndex} carte(s) à réviser plus tard.</p>
          <button onClick={() => window.location.reload()}>Reprendre plus tard</button>
        </div>
      ) : cards.length === 0 ? (
        <div>
          <h1>Aucune carte à réviser aujourd'hui</h1>
          <p>Reviens plus tard !</p>
        </div>
      ) : (
        <div>
          <h1>Révision</h1>
          <p>Carte {currentIndex + 1} sur {cards.length}</p>
          <p>Boîte n° : {card.box}</p>
          <h2>Question : {card.question}</h2>

          {showAnswer ? (
            <div>
              <p>Réponse : {card.answer}</p>
              <button onClick={() => submitAnswer(true)}>Je connaissais la réponse</button>
              <button onClick={() => submitAnswer(false)}>Je ne connaissais pas</button>
            </div>
          ) : (
            <button onClick={() => setShowAnswer(true)}>Afficher la réponse</button>
          )}

          <br />
          <button onClick={stopSession}>Arrêter la session</button>
        </div>
      )}
    </div>
  )
}