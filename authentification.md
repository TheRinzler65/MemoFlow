# Plan d'implémentation de l'Authentification

---

## 1. Modifications Backend (PHP)

Nous devons créer la route et le contrôleur pour vérifier la session.

### [MODIFY] `backend/public/index.php`
Ajouter la route `/me` aux routes publiques.
```diff
    // PUBLIC
    [
        'prefix' => '',
        'middlewares' => [],
        'routes' => [
            ['POST', '/register', 'AuthController#register', 'register'], // http://localhost:8000/api/v1/register
            ['POST', '/login', 'AuthController#login', 'login'], // http://localhost:8000/api/v1/login
            ['POST', '/logout', 'AuthController#logout', 'logout'], //http://localhost:8000/api/v1/logout
+           ['GET', '/me', 'AuthController#me', 'me'], // http://localhost:8000/api/v1/me
        ]
    ],
```

### [MODIFY] `backend/src/Controllers/AuthController.php`
Ajouter la méthode `me()` en utilisant vos helpers existants (`Json` et `Error`).
```php
    public function me(): void
    {
        if (isset($_SESSION["user"])) {
            Json::send([
                "status" => "success",
                "user" => $_SESSION["user"]
            ], 200);
        } else {
            Error::sendError("Non authentifié", 401);
        }
    }
```

---

## 2. Modifications Frontend (React/TypeScript)

### [MODIFY] `frontend/src/lib/api.ts`
Puisque nous utilisons Axios, la meilleure pratique est de créer une instance configurée pour toujours envoyer les cookies de session. Ceci est donc à modifier dans src/lib/api.ts.
```typescript
import axios from 'axios';

export const api = axios.create({
    baseURL: '/api/v1',
    withCredentials: true, // INDISPENSABLE pour que PHP lise le PHPSESSID
});
```

### [NEW] `frontend/src/contexts/AuthContext.tsx`
C'est le cœur du système. Il contient les types, le contexte, et le provider.
```tsx
import { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import { api } from '@/lib/axios';

// 1. Définition des Types
export type User = {
    id: number;
    email: string;
    name: string;
};

type AuthContextType = {
    user: User | null;
    isLoading: boolean;
    login: (userData: User) => void;
    logout: () => Promise<void>;
};

// 2. Création du Contexte
const AuthContext = createContext<AuthContextType | undefined>(undefined);

// 3. Création du Provider
export const AuthProvider = ({ children }: { children: ReactNode }) => {
    const [user, setUser] = useState<User | null>(null);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        const checkAuth = async () => {
            try {
                // Requête vers la route /me que nous avons créé
                const response = await api.get('/me');
                if (response.data.status === 'success') {
                    setUser(response.data.user);
                }
            } catch (error) {
                // Si 401 (Non authentifié), on ne fait rien, user reste null
                setUser(null);
            } finally {
                setIsLoading(false); // Dans tous les cas, le chargement est terminé
            }
        };

        checkAuth();
    }, []);

    const login = (userData: User) => {
        setUser(userData);
    };

    const logout = async () => {
        try {
            await api.post('/logout'); // Appel à l'API de déconnexion existante
        } catch (error) {
            console.error("Erreur lors de la déconnexion", error);
        } finally {
            setUser(null); // On supprime l'utilisateur localement dans tous les cas
        }
    };

    return (
        <AuthContext.Provider value={{ user, isLoading, login, logout }}>
            {children}
        </AuthContext.Provider>
    );
};

// 4. Création du Hook personnalisé
export const useAuth = () => {
    const context = useContext(AuthContext);
    if (context === undefined) {
        throw new Error('useAuth doit être utilisé dans un AuthProvider');
    }
    return context;
};
```

### [MODIFY] `frontend/src/main.tsx`
Nous devons englober l'application (`App`) avec le `AuthProvider`.
```tsx
import { createRoot } from "react-dom/client"
import { ThemeProvider } from "@/components/theme-provider.tsx"
import { Toaster } from "sonner"
import App from "./App.tsx"
import "./index.css"
import { AuthProvider } from "./contexts/AuthContext.tsx" // <-- IMPORT

createRoot(document.getElementById("root")!).render(
  <ThemeProvider>
    <AuthProvider>  {/* <-- ENVELOPPEMENT */}
      <App />
      <Toaster />
    </AuthProvider>
  </ThemeProvider>
)
```

---

## Plan de Vérification
Une fois ces modifications appliquées, nous pouvons utiliser `const { user, isLoading, logout } = useAuth();` dans n'importe quel composant (comme notre dashboard). 
