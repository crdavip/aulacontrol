export const getDataHistory = async (API) => {
    const res = await fetch(`${API}`);
    const data = await res.json();
    return data;
}