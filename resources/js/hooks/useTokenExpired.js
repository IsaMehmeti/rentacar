import { jwtDecode } from "jwt-decode";

export function useTokenExpired(token) {
    if (!token) return true; // Treat no token as expired

    const decodedToken = jwtDecode(token);
    const currentTime = Date.now() / 1000;
    return decodedToken.exp < currentTime;
}
