import { useState } from "react"
import axios from 'axios'

function App() {
    const [form, setForm] = useState({
        name: '',
        email: '',
        phone: '',
        price: '',
    }); 

    const handleChange = (event) => {
        setForm({
          ...form,
          [event.target.name]: event.target.value
        });
      };


    const sendData = (event) => {
        event.preventDefault(); 

        const formData = new FormData();
        formData.append('name', form.name);
        formData.append('email', form.email);
        formData.append('price', form.price);
        formData.append('phone', form.phone);

        axios.post('http://server/controller/create_lead.php', formData)
            .then(response => { console.log(response.data); })
            .catch(error => { console.error(error); });
    }

    return (
        <div>
            <h1>Форма</h1>
            <form>
                <label htmlFor='name'>Имя</label>
                <br />
                <input type='text' id='name' name='name' onChange={handleChange} required></input>
                <br />
                <label htmlFor='email'>Почта</label>
                <br />
                <input type='email' id='email' name='email' onChange={handleChange}  required></input>
                <br />
                <label htmlFor='phone'>Телефон</label>
                <br />
                <input type='tel' id='phone' name='phone' onChange={handleChange} required></input>
                <br />
                <label htmlFor='price'>Цена</label>
                <br />
                <input type='number' id='price' name='price' onChange={handleChange} required></input>
                <br/>
                <input type='submit' id='submit' name='submit' onClick={sendData}></input>
            </form>
        </div>
    )
}

export default App