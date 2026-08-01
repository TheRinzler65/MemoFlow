import { RegisterCard } from "@/components/shadcnblocs/RegisterCard"

export const Register = () => {
  return (
    <div>
      <RegisterCard
        logo={{
          title: "Logo de MemoFlow",
          src: "/logo.svg",
          url: "/",
          alt: "Logo de MemoFlow",
        }}
      />
    </div>
  )
}
