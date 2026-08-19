import api from "@/lib/api"
import { useAuth } from "@/contexts/AuthContext"
import { useState, type FormEvent } from "react"
import { useNavigate } from "react-router-dom"

export const CreateDeck = () => {
  const [title, setTitle] = useState("")
  const [description, setDescription] = useState("")
  const [error, setError] = useState<null | string>(null)

  const navigate = useNavigate()
  const { user } = useAuth()

  const handleSubmit = async (event: FormEvent) => {
    event.preventDefault()

    try {
      const response = await api.post("/decks/create", {
        title,
        description,
        user_id: user?.id,
      })

      if (response.data.status === "success") {
        navigate("/decks")
      } else {
        setError("Erreur")
      }
    } catch (error) {
      setError("Erreur lors de la création du deck")
      console.error("Erreur lors de la création du deck", error)
    }
  }

  return (
    <div>
      <h1>Créer un deck</h1>
      {error && <p>{error}</p>}
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
        <button type="submit">Créer</button>
      </form>
    </div>
  )
}