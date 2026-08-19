import api from "@/lib/api"
import { useEffect, useState } from "react"
import { Link, useParams } from "react-router-dom"

interface Card {
  id: number
  question: string
  answer: string
  box: number
  next_review: string
  last_review: string
  created_at: string
  updated_at: string
}

export const ShowCard = () => {
  const [cards, setCards] = useState<Card[]>([])
  const [loading, setLoading] = useState<boolean>(true)
  const [error, setError] = useState<null | string>(null)

  const { id } = useParams()

  useEffect(() => {
    const getCards = async () => {
      try {
        setLoading(true)
        const response = await api.get(`/cards/deck/${id}`)
        if (response.data.status === "success") {
          setCards(response.data.cards)
        } else {
          setError("Erreur")
        }
      } catch (error) {
        setError("Erreur lors de la récupération des cards")
        console.error("Erreur lors de la récupération des cards", error)
      } finally {
        setLoading(false)
      }
    }

    getCards()
  }, [id])

  const removeCard = async (cardId: number) => {
    if (!confirm("Supprimer cette carte ?")) {
      return
    }

    try {
      await api.delete(`/cards/remove/${cardId}`)
      setCards(cards.filter((card) => card.id !== cardId))
    } catch (error) {
      setError("Erreur lors de la suppression de la carte")
      console.error("Erreur lors de la suppression de la carte", error)
    }
  }

  return (
    <div>
      {/* Plus tard : bouton pour ajouter une carte à ce deck directement (deck_id = {id})
      <Link to={`/cards/create?deck_id=${id}`}>Ajouter une carte</Link>
      */}
      {loading ? (
        "Chargement..."
      ) : error ? (
        error
      ) : (
        <ul>
          {cards.map((card) => (
            <li key={card.id}>
              <h1>Question : {card.question}</h1>
              <p>Réponse : {card.answer}</p>
              <p>Boîte n° : {card.box}</p>
              <p>Prochaine révision : {card.next_review}</p>
              <p>Dernière révision : {card.last_review}</p>
              <p>Crée le : {card.created_at}</p>
              <p>Mis à jour le : {card.updated_at}</p>
              <p>ID : {card.id}</p>
              <Link to={`/cards/edit/${card.id}`}>Modifier</Link>
              <button onClick={() => removeCard(card.id)}>Supprimer</button>
              <hr />
            </li>
          ))}
        </ul>
      )}
    </div>
  )
}