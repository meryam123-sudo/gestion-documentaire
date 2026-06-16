import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";

import "./App.css";

import Login from "./pages/Login";
import Register from "./pages/Register";
import Dashboard from "./pages/Dashboard";
import Documents from "./pages/Documents";
import AjouterDocument from "./pages/AjouterDocument";
import ModifierDocument from "./pages/ModifierDocument";
import Archivage from "./pages/Archivage";
import Validations from "./pages/Validations";
import Historique from "./pages/Historique";
import Utilisateurs from "./pages/Utilisateurs";
import Notifications from "./pages/Notifications";
import Roles from "./pages/Roles";

import ProtectedRole from "./components/ProtectedRole";

function PrivateRoute({ children }) {
  const token = localStorage.getItem("token");

  return token ? children : <Navigate to="/" />;
}

function App() {
  return (
    <BrowserRouter>
      <Routes>
        {/* Routes publiques */}
        <Route path="/" element={<Login />} />

        <Route
          path="/register"
          element={<Register />}
        />

        {/* Dashboard */}
        <Route
          path="/dashboard"
          element={
            <PrivateRoute>
              <Dashboard />
            </PrivateRoute>
          }
        />

        {/* Documents */}
        <Route
          path="/documents"
          element={
            <PrivateRoute>
              <Documents />
            </PrivateRoute>
          }
        />

        <Route
          path="/documents/ajouter"
          element={
            <PrivateRoute>
              <AjouterDocument />
            </PrivateRoute>
          }
        />

        <Route
          path="/documents/:id/modifier"
          element={
            <PrivateRoute>
              <ModifierDocument />
            </PrivateRoute>
          }
        />

        {/* Validations */}
        <Route
          path="/validations"
          element={
            <PrivateRoute>
              <ProtectedRole
                allowedRoles={[
                  "Administrateur Principal",
                  "Responsable Validation",
                ]}
              >
                <Validations />
              </ProtectedRole>
            </PrivateRoute>
          }
        />

        {/* Archivage */}
        <Route
          path="/archivage"
          element={
            <PrivateRoute>
              <ProtectedRole
                allowedRoles={[
                  "Administrateur Principal",
                  "Archiviste",
                ]}
              >
                <Archivage />
              </ProtectedRole>
            </PrivateRoute>
          }
        />

        {/* Historique */}
        <Route
          path="/historique"
          element={
            <PrivateRoute>
              <Historique />
            </PrivateRoute>
          }
        />

        {/* Notifications */}
        <Route
          path="/notifications"
          element={
            <PrivateRoute>
              <Notifications />
            </PrivateRoute>
          }
        />

        {/* Utilisateurs */}
        <Route
          path="/utilisateurs"
          element={
            <PrivateRoute>
              <ProtectedRole
                allowedRoles={[
                  "Administrateur Principal",
                ]}
              >
                <Utilisateurs />
              </ProtectedRole>
            </PrivateRoute>
          }
        />

        {/* Rôles */}
        <Route
          path="/roles"
          element={
            <PrivateRoute>
              <ProtectedRole
                allowedRoles={[
                  "Administrateur Principal",
                ]}
              >
                <Roles />
              </ProtectedRole>
            </PrivateRoute>
          }
        />

        {/* Route par défaut */}
        <Route
          path="*"
          element={<Navigate to="/" />}
        />
      </Routes>
    </BrowserRouter>
  );
}

export default App;

