import React from "react";

const Pagination = ({currentPage, itemsPerPage, length, onPageChanged, size}) => {

    const pagesCount = Math.ceil(length / itemsPerPage);
    let pages = [];
    const start = (currentPage * itemsPerPage - itemsPerPage) + 1;
    const end = start - 1 + itemsPerPage;

    const taille = size ? size : "pagination-xl"

    if(pagesCount > 10) {
        const array1 = [1, 2, 3, 4]
        const array2 = [pagesCount-3, pagesCount-2, pagesCount-1, pagesCount]
        if(array1.includes(currentPage)){
            pages = [1, 2, 3, 4, 'XX1', pagesCount-1, pagesCount]
        } else if (array2.includes(currentPage)) {
            pages = [1, 2, 'XX1', pagesCount-3, pagesCount-2, pagesCount-1, pagesCount]
        } else {
            pages = [1, 2, 'XX1', currentPage-1, currentPage, currentPage+1, 'XX2', pagesCount-1, pagesCount]
        }
    } else {
        for(let i = 1; i <= pagesCount; i++) {
            pages.push(i)
        }
    }

    return (
        <>
            <div className="d-flex justify-content-center">
                <ul className={"pagination " + taille}>
                    <li className={"page-item" + (currentPage === 1 && " disabled")}>
                        <button className="page-link" onClick={() => onPageChanged(currentPage - 1)}>&laquo;</button>
                    </li>

                    {pages.map(page => {
                        if(page === 'XX1' || page === 'XX2'){
                            return (
                                <li key={page} className={"page-item disabled"}>
                                    <button className="page-link">...</button>
                                </li>
                            )
                        }
                        return (
                            <li key={page} className={"page-item" + (currentPage === page && " active")}>
                                <button className="page-link" onClick={() => onPageChanged(page)}>{page}</button>
                            </li>
                        )
                    })}

                    <li className={"page-item" + (currentPage === pagesCount && " disabled")}>
                        <button className="page-link" onClick={() => onPageChanged(currentPage + 1)}>&raquo;</button>
                    </li>
                </ul>
            </div>

            <span className={"mt-2"}>Afficher {start}-{end} sur {length}</span>
        </>
    );
};

Pagination.getData = (items, currentPage, itemsPerPage) => {
    const start = currentPage * itemsPerPage - itemsPerPage;
    return items.slice(start, start + itemsPerPage);
}

export default Pagination;