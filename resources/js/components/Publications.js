import React from 'react';

function Publications(props) {
    const isLoggedIn = props.isLoggedIn;
    if (isLoggedIn) {
        return (
            <div>
                Se inicio sesion;
            </div>
        );
    }
    return (
        <div className="container-fluid">
            <div className="row justify-content-center">
                <div className="col-md-12">
                    <div className="card">
                        <div className="card-header">Listado de Publicaciones</div>
                        <div className="card-body">Aqui ira el listado de publicaciones</div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Publications;
