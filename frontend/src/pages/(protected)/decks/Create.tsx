import { Button } from "@/components/ui/button"
import {
  Card,
  CardContent,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import { Field, FieldError, FieldLabel } from "@/components/ui/field"
import { Input } from "@/components/ui/input"
import { Textarea } from "@/components/ui/textarea"
import { useAuth } from "@/contexts/AuthContext"
import api from "@/lib/api"
import { createDeckSchema, type CreateDeckSchema } from "@/lib/schemas/deck"
import { zodResolver } from "@hookform/resolvers/zod"
import { useState } from "react"
import { Controller, useForm } from "react-hook-form"
import { useNavigate } from "react-router-dom"

export const CreateDeck = () => {
  const [error, setError] = useState<null | string>(null)
  const [isLoading, setIsLoading] = useState(false)

  const navigate = useNavigate()
  const { user } = useAuth()

  const form = useForm<CreateDeckSchema>({
    resolver: zodResolver(createDeckSchema),
    defaultValues: {
      title: "",
      description: "",
    },
  })

  const onSubmit = async (data: CreateDeckSchema) => {
    setError(null)
    setIsLoading(true)
    const safeData = createDeckSchema.safeParse(data)

    if (!safeData.success) {
      console.log("Erreur")
      setError("Verifier vos informations")
      setIsLoading(false)
      return
    }

    try {
      const response = await api.post("/decks/create", {
        title: safeData.data.title,
        description: safeData.data.description,
        user_id: user?.id,
      })

      if (response.data.status === "success") {
        navigate("/decks")
      } else {
        setError("Erreur lors de la création")
      }
    } catch (err) {
      setError("Erreur lors de la création du deck")
      console.error("Erreur lors de la création du deck", err)
    } finally {
      setIsLoading(false)
    }
  }

  return (
    <div className="flex min-h-screen items-center justify-center p-4">
      <Card className="w-full max-w-md rounded-xl">
        <CardHeader>
          <CardTitle>
            <h1>Créer un deck</h1>
          </CardTitle>
        </CardHeader>

        {error && (
          <div className="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            {error}
          </div>
        )}
        <form onSubmit={form.handleSubmit(onSubmit)} noValidate>
          <CardContent className="flex flex-col gap-4">
            <div className="flex flex-col gap-1.5">
              <Controller
                control={form.control}
                name="title"
                render={({ field, fieldState }) => (
                  <Field data-invalid={fieldState.invalid}>
                    <FieldLabel htmlFor={field.name}>Titre</FieldLabel>
                    <Input
                      {...field}
                      id={field.name}
                      type="text"
                      placeholder="Mathématique"
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
                name="description"
                render={({ field, fieldState }) => (
                  <Field data-invalid={fieldState.invalid}>
                    <FieldLabel htmlFor={field.name}>Description</FieldLabel>
                    <Textarea
                      {...field}
                      id={field.name}
                      value={field.value ?? ""}
                      placeholder="Description rapide du contenu du deck..."
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
          </CardContent>

          <CardFooter className="mt-2 flex items-center justify-end gap-3">
            <Button
              type="button"
              onClick={() => navigate("/decks")}
              variant={"outline"}
            >
              Annuler
            </Button>
            <Button type="submit" disabled={isLoading}>
              {isLoading ? "Création..." : "Créer le deck"}
            </Button>
          </CardFooter>
        </form>
      </Card>
    </div>
  )
}
