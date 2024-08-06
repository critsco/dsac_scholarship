import React from "react";

export default function RegisterRigthStep(props) {
    const { current } = props;

    return (
        <div className="steps">
            <div
                className={`step-item step-item-0 ${
                    current === 0 || current === 1 || current === 2
                        ? "active"
                        : ""
                }`}
            >
                <div className="step-item-container">
                    <div className="step-item-icon step-1">
                        <p>1</p>
                    </div>

                    <div className="step-item-tail mb-15 ">
                        <div className="circle-1 ml-20 step-1"></div>
                    </div>
                </div>
            </div>
            <div
                className={`step-item step-item-1 ${
                    current === 3 || current === 4 ? "active" : ""
                }`}
            >
                <div className="step-item-container">
                    <div className="step-item-icon step-2">2</div>

                    <div className="step-item-tail mb-15 ">
                        <div className="circle-2 ml-20 step-2"></div>
                    </div>
                </div>
            </div>
            <div
                className={`step-item step-item-2 ${
                    current === 5 ? "active" : ""
                }`}
            >
                <div className="step-item-container">
                    <div className="step-item-icon step-3">3</div>

                    <div className="step-item-tail mb-15 ">
                        <div className="circle-3 ml-20 step-3"></div>
                    </div>
                </div>
            </div>
            <div
                className={`step-item step-item-3 ${
                    current === 6 ? "active" : ""
                }`}
            >
                <div className="step-item-container">
                    <div className="step-item-icon step-4">4</div>

                    <div className="step-item-tail mb-15 ">
                        <div className="circle c-4"></div>
                    </div>
                </div>
            </div>
        </div>
    );
}
