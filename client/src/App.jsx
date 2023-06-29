import { useState } from "react"

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


    const sendData = (data) => {
        console.log('');
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
                <input type='submit' id='submit' name='submit'></input>
            </form>
        </div>
    )
}

export default App