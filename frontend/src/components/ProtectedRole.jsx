import { Navigate } from "react-router-dom";

export default function ProtectedRole({
  children,
  allowedRoles,
}) {
  const utilisateur = JSON.parse(
    localStorage.getItem("utilisateur")
  );

  const role = utilisateur?.role?.nomRole;

  if (!allowedRoles.includes(role)) {
    return <Navigate to="/dashboard" />;
  }

  return children;
}