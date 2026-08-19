import {
  Card,
  CardContent,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import api from "@/lib/api"
import { createCardSchema, type CreateCardSchema } from "@/lib/schemas/card"
import { Field, FieldError, FieldLabel } from "@/components/ui/field"
import { zodResolver } from "@hookform/resolvers/zod"
import { useEffect, useState } from "react"
import { Controller, useForm } from "react-hook-form"
import { useNavigate } from "react-router-dom"
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Textarea } from "@/components/ui/textarea"

interface Deck {
  id: number
  title: string
}

export const CreateCard = () => {
  const [decks, setDecks] = useState<Deck[]>([])
  const [selectedDeckId, setSelectedDeckId] = useState<number | null>(null)
  const [isLoading, setIsLoading] = useState(false)
  const [error, setError] = useState<null | string>(null)

  const navigate = useNavigate()

  const form = useForm<CreateCardSchema>({
    resolver: zodResolver(createCardSchema),
    defaultValues: {
      question: "",
      answer: "",
    },
  })

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

  const onSubmit = async (data: CreateCardSchema) => {
    setError(null)
    setIsLoading(true)
    const safeData = createCardSchema.safeParse(data)

    if (!safeData.success) {
      console.log("Erreur")
      setError("Verifier vos informations")
      setIsLoading(false)
      return
    }

    try {
      const response = await api.post("/cards/create", {
        question: safeData.data.question,
        answer: safeData.data.answer,
        deck_id: selectedDeckId,
      })

      if (response.data.status === "success") {
        navigate("/cards")
      } else {
        setError("Erreur")
      }
    } catch (error) {
      setError("Erreur lors de la création de la carte")
      console.error("Erreur lors de la création de la carte", error)
    } finally {
      setIsLoading(false)
    }
  }

  const selectedDeck = decks.find((d) => d.id === selectedDeckId)

  return (
    <div className="flex min-h-screen items-center justify-center p-4">
      <Card className="w-full max-w-md rounded-xl">
        <CardHeader>
          <CardTitle>
            <h1>Créer une carte</h1>
          </CardTitle>
        </CardHeader>

        {error && <div className="mb-4 rounded-lg border">{error}</div>}
        <form onSubmit={form.handleSubmit(onSubmit)} noValidate>
          <CardContent className="flex flex-col gap-4">
            <div className="flex flex-col gap-1.5">
              <Controller
                control={form.control}
                name="question"
                render={({ field, fieldState }) => (
                  <Field data-invalid={fieldState.invalid}>
                    <FieldLabel htmlFor={field.name}>Question</FieldLabel>
                    <Input
                      {...field}
                      id={field.name}
                      type="text"
                      placeholder="Insérer votre question"
                      className="text-sm"
                      aria-invalid={fieldState.invalid}
                    />
                    {fieldState.invalid && (
                      <FieldError errors={[fieldState.error]} />
                    )}
                  </Field>
                )}
              />
            </div>

            <div className="flex flex-col gap-1.5">
              <Controller
                control={form.control}
                name="answer"
                render={({ field, fieldState }) => (
                  <Field data-invalid={fieldState.invalid}>
                    <FieldLabel htmlFor={field.name}>Réponse</FieldLabel>
                    <Textarea
                      {...field}
                      id={field.name}
                      placeholder="Insérer votre réponse"
                      className="text-sm"
                      aria-invalid={fieldState.invalid}
                      rows={4}
                    />
                    {fieldState.invalid && (
                      <FieldError errors={[fieldState.error]} />
                    )}
                  </Field>
                )}
              />
            </div>

            <Select value={selectedDeckId} onValueChange={setSelectedDeckId}>
              <SelectTrigger className="w-full max-w-64">
                <SelectValue placeholder="Sélectionnez un deck">
                  {selectedDeck ? selectedDeck.title : undefined}
                </SelectValue>
              </SelectTrigger>
              <SelectContent>
                <SelectGroup>
                  {decks.map((deck) => (
                    <SelectItem key={deck.id} value={deck.id}>
                      {deck.title}
                    </SelectItem>
                  ))}
                </SelectGroup>
              </SelectContent>
            </Select>
          </CardContent>

          <CardFooter className="mt-2 flex items-center justify-end gap-3">
            <Button
              type="button"
              onClick={() => navigate("/cards")}
              variant={"outline"}
            >
              Annuler
            </Button>
            <Button type="submit" disabled={isLoading}>
              {isLoading ? "Création..." : "Créer la carte"}
            </Button>
          </CardFooter>
        </form>
      </Card>
    </div>
  )
}
