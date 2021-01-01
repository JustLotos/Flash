export default interface RegisterByEmailResponse{
    token: string;
    refreshToken: string;
    role: string;
    status: string;
    email: string;
    id: string;
}