import {
  Empty,
  EmptyContent,
  EmptyHeader,
  EmptyMedia,
  EmptyTitle,
} from "@/components/ui/empty"
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"
import { Eye, FileQuestionMark, Pencil, Trash } from "lucide-react"
import { Link } from "react-router-dom"
import { Button, buttonVariants } from "@/components/ui/button"
import he from "he"
import type { Deck } from "@/types/Deck"

interface DeckTableProps {
  decks: Deck[]
  removeDeck: (value: number) => Promise<void>
}

export const DeckTable = ({ decks, removeDeck }: DeckTableProps) => {
  return (
    <Table className="mx-auto max-w-xl">
      <TableHeader>
        <TableRow>
          <TableHead className="w-25">Titre</TableHead>
          <TableHead>Description</TableHead>
          <TableHead>Crée le :</TableHead>
          <TableHead className="text-right">Actions</TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        {decks.length <= 0 ? (
          <TableRow>
            <TableCell colSpan={4}>
              <Empty>
                <EmptyHeader>
                  <EmptyMedia variant="icon">
                    <FileQuestionMark />
                  </EmptyMedia>
                  <EmptyTitle>Aucun jeu de carte disponible</EmptyTitle>
                </EmptyHeader>
                <EmptyContent>
                  <Link to="/decks/create" className={buttonVariants()}>
                    Créer un deck
                  </Link>
                </EmptyContent>
              </Empty>
            </TableCell>
          </TableRow>
        ) : (
          decks.map((deck) => (
            <TableRow key={deck.id}>
              <TableCell className="font-medium">
                {he.decode(deck.title)}
              </TableCell>
              <TableCell>
                {deck.description ? he.decode(deck.description) : "---"}
              </TableCell>
              <TableCell>{deck.created_at}</TableCell>
              <TableCell className="flex justify-end gap-1 text-right">
                <Link to={`/decks/${deck.id}`} className={buttonVariants()}>
                  <Eye />
                </Link>
                <Link
                  to={`/decks/edit/${deck.id}`}
                  className={buttonVariants({ variant: "secondary" })}
                >
                  <Pencil />
                </Link>
                <Button
                  variant={"destructive"}
                  onClick={() => removeDeck(deck.id)}
                >
                  <Trash />
                </Button>
              </TableCell>
            </TableRow>
          ))
        )}
      </TableBody>
    </Table>
  )
}
