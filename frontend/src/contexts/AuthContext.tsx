import { createContext, useContext, useState, useEffect, useCallback, type ReactNode } from 'react';
import { api } from '@/lib/api';

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

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider = ({ children }: { children: ReactNode }) => {
    const [user, setUser] = useState<User | null>(null);
    const [isLoading, setIsLoading] = useState(true);

    const checkAuth = useCallback(async () => {
        try {
            const response = await api.get('/me');
            if (response.data.status === 'success') {
                setUser(response.data.user);
            }
        } catch (error) {
            setUser(null);
        } finally {
            setIsLoading(false);
        }
    }, []);

    useEffect(() => {
        checkAuth();
    }, [checkAuth]);

    // Rafraîchit la session quand l'onglet redevient actif :
    // l'expiration glisse avec la dernière requête authentifiée, même en navigation SPA
    useEffect(() => {
        const handleVisibility = () => {
            if (document.visibilityState === 'visible') {
                checkAuth();
            }
        };

        document.addEventListener('visibilitychange', handleVisibility);

        return () => document.removeEventListener('visibilitychange', handleVisibility);
    }, [checkAuth]);

    const login = (userData: User) => {
        setUser(userData);
    };

    const logout = async () => {
        try {
            await api.post('/logout');
        } catch (error) {
            console.error("Erreur lors de la déconnexion", error);
        } finally {
            setUser(null);
        }
    };

    return (
        <AuthContext.Provider value={{ user, isLoading, login, logout }}>
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => {
    const context = useContext(AuthContext);
    if (context === undefined) {
        throw new Error('useAuth doit être utilisé dans un AuthProvider');
    }
    return context;
};