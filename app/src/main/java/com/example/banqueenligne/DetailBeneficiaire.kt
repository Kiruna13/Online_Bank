package com.example.banqueenligne

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.view.Window
import android.widget.Button
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity

class DetailBeneficiaire : AppCompatActivity(), View.OnClickListener {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        requestWindowFeature(Window.FEATURE_NO_TITLE)
        setContentView(R.layout.activity_detail_beneficiaire)

        val data = intent
        val rib = data.getStringExtra("rib")
        val name = data.getStringExtra("name")

        findViewById<TextView>(R.id.rib).text = rib
        findViewById<TextView>(R.id.name).text = name

        val b = findViewById<Button>(R.id.btn_virement)
        b.setOnClickListener(this)

        //actionbar
        val actionbar = supportActionBar
        //set actionbar title
        actionbar!!.title = "Détails bénéficiaire"
        //set back button
        actionbar.setDisplayHomeAsUpEnabled(true)
    }

    override fun onSupportNavigateUp(): Boolean {
        onBackPressed()
        return true
    }

    override fun onClick(view: View) {
        when (view.getId()) {
            R.id.btn_virement -> startActivity(
                Intent(
                    this@DetailBeneficiaire,
                    MakeVirement::class.java
                )
            )
        }
    }
}