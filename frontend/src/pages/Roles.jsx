import { useEffect, useState } from "react";
import Layout from "../components/Layout";
import api from "../services/api";

export default function Roles() {
  const [roles, setRoles] = useState([]);

  const chargerRoles = async () => {
    const res = await api.get("/roles");
    setRoles(res.data.data || res.data);
  };

  useEffect(() => {
    chargerRoles();
  }, []);

  return (
    <Layout>
      <div className="page-header">
        <h1>Rôles</h1>
        <p>Liste des rôles disponibles</p>
      </div>

      <div className="table-card">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nom du rôle</th>
              <th>Description</th>
            </tr>
          </thead>

          <tbody>
            {roles.map((role) => (
              <tr key={role.idRole}>
                <td>{role.idRole}</td>
                <td>{role.nomRole}</td>
                <td>{role.description}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </Layout>
  );
}