import { Button, Layout } from "antd";

export default function PageLanding() {
	return (
		<Layout.Content>
			<Layout.Header>
				<div className="left-menu">
					<div className="logo-wrapper">
						<img src="/assets/images/logo_sidemenu.png" alt="logo_sidemenu" />
					</div>
				</div>
				<div className="right-menu">
					<Button type="link" className="btn-menu">
						HOME
					</Button>
					<Button type="link" className="btn-menu">
						PRODUCTS
					</Button>
					<Button type="link" className="btn-menu">
						ABOUT
					</Button>
					<Button type="link" className="btn-menu">
						CONTACT
					</Button>
				</div>
			</Layout.Header>

			<div className="section carousel-wrapper"></div>

			<div className="section products-wrapper"></div>

			<div className="section about-wrapper"></div>

			<div className="section contact-wrapper"></div>
		</Layout.Content>
	);
}
