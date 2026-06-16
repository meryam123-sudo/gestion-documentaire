import { useEffect, useState } from "react";
import Layout from "../components/Layout";
import api from "../services/api";

export default function Utilisateurs() {
  const [utilisateurs, setUtilisateurs] = useState([]);

  const chargerUtilisateurs = async () => {
    const res = await api.get("/utilisateurs");
    setUtilisateurs(res.data.data || res.data);
  };

  useEffect(() => {
    chargerUtilisateurs();
  }, []);

  const desactiver = async (id) => {
    await api.delete(`/utilisateurs/${id}`);
    chargerUtilisateurs();
  };

  return (
    <Layout>
      <div className="page-header">
        <h1>Utilisateurs</h1>
        <p>Gestion des comptes utilisateurs</p>
      </div>

      <div className="table-card">
        <table>
          <thead>
            <tr>
              <th>Nom</th>
              <th>Email</th>
              <th>Rôle</th>
              <th>Actif</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
            {utilisateurs.map((u) => (
              <tr key={u.idUtilisateur}>
                <td>{u.prenom} {u.nom}</td>
                <td>{u.email}</td>
                <td>{u.role?.nomRole}</td>
                <td>{u.actif ? "Oui" : "Non"}</td>
                <td>
                  {u.actif && (
                    <button
                      className="small-btn"
                      onClick={() => desactiver(u.idUtilisateur)}
                    >
                      Désactiver
                    </button>
                  )}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </Layout>
  );
}