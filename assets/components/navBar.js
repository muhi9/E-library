import { useRef } from "react";
import React from 'react';
import "../styles/navbar.css";

function Navbar() {
    const navRef = useRef();

    const showNavbar = () => {
        navRef.current.classList.toggle(
            "responsive_nav"
        );
    };

    return (
        <header>
            <h3>LYBRARY</h3>
            <nav ref={navRef}>
                <a href="/homepage">Home</a>
                <div className="btn-group">
                    <button className="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Book
                    </button>
                    <ul className="dropdown-menu">
                    <li><a className="dropdown-item" href="/book/add">Add</a></li>
                    <li><a className="dropdown-item" href="/book/edit">Edit</a></li>
                    <li><a className="dropdown-item" href="/book">List</a></li>
                    </ul>
                </div>
                <a href="/user">Users</a>
                <a className="logout" href="/logout">Logout</a>
                <button
                    className="nav-btn nav-close-btn"
                    onClick={showNavbar}>

                </button>
            </nav>
            <button
                className="nav-btn"
                onClick={showNavbar}>

            </button>
        </header>
    );
}

export default Navbar;